<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmployeeDataLogPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionName = 'View Employee Data Log';

        $permission = Permission::firstOrCreate(
            ['name' => $permissionName, 'guard_name' => 'web'],
            ['name' => $permissionName, 'guard_name' => 'web']
        );

        $roleIds = Role::whereIn('name', ['company', 'hr'])->pluck('id');
        $existing = DB::table('role_has_permissions')
            ->where('permission_id', $permission->id)
            ->whereIn('role_id', $roleIds)
            ->pluck('role_id')
            ->all();

        $rows = [];
        foreach ($roleIds as $roleId) {
            if (!in_array($roleId, $existing, true)) {
                $rows[] = [
                    'permission_id' => $permission->id,
                    'role_id' => $roleId,
                ];
            }
        }

        if (!empty($rows)) {
            DB::table('role_has_permissions')->insert($rows);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
