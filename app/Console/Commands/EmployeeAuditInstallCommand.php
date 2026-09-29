<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\EmployeeDataChangeLogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmployeeAuditInstallCommand extends Command
{
    protected $signature = 'employee-audit:install {--migrate : Run migrations}';

    protected $description = 'Install employee data audit: migrate (optional), default setting, and View Employee Data Log permission';

    public function handle(EmployeeDataChangeLogService $logService)
    {
        if ($this->option('migrate')) {
            $this->info('Running migrations...');
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations/2026_09_22_110000_create_employee_data_change_logs_table.php',
            ]);
            $this->line(Artisan::output());
        } else {
            $this->warn('Skipping migrate. Run with --migrate or: php artisan migrate');
        }

        $permissionName = 'View Employee Data Log';
        $permission = Permission::firstOrCreate(
            ['name' => $permissionName, 'guard_name' => 'web'],
            ['name' => $permissionName, 'guard_name' => 'web']
        );
        $this->info("Permission ready: {$permissionName} (#{$permission->id})");

        $roleIds = Role::whereIn('name', ['company', 'hr'])->pluck('id');
        $existing = DB::table('role_has_permissions')
            ->where('permission_id', $permission->id)
            ->whereIn('role_id', $roleIds)
            ->pluck('role_id')
            ->all();

        $rows = [];
        $nowMissing = 0;
        foreach ($roleIds as $roleId) {
            if (!in_array($roleId, $existing, true)) {
                $rows[] = [
                    'permission_id' => $permission->id,
                    'role_id' => $roleId,
                ];
                $nowMissing++;
            }
        }
        if (!empty($rows)) {
            DB::table('role_has_permissions')->insert($rows);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->info("Assigned permission to {$nowMissing} company/hr roles (bulk).");

        $companyIds = User::where('type', 'company')->pluck('id');
        $existingSettings = DB::table('settings')
            ->where('name', EmployeeDataChangeLogService::SETTING_KEY)
            ->whereIn('created_by', $companyIds)
            ->pluck('created_by')
            ->all();

        $settingRows = [];
        $now = now();
        foreach ($companyIds as $companyId) {
            if (!in_array($companyId, $existingSettings, false)) {
                $settingRows[] = [
                    'value' => 'on',
                    'name' => EmployeeDataChangeLogService::SETTING_KEY,
                    'created_by' => $companyId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        if (!empty($settingRows)) {
            foreach (array_chunk($settingRows, 200) as $chunk) {
                DB::table('settings')->insert($chunk);
            }
        }
        $this->info('Default setting employee_audit_log_enabled=on ensured for ' . $companyIds->count() . ' companies.');
        $this->info('Employee audit install complete.');

        return Command::SUCCESS;
    }
}
