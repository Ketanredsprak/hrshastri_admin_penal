<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\EmployeeDataChangeLogService;
use App\Services\EmployeeDataSnapshotService;
use Illuminate\Console\Command;

class EmployeeAuditTestLogCommand extends Command
{
    protected $signature = 'employee-audit:test-log {employeeId : Internal employees.id}';

    protected $description = 'Dry-run: capture snapshot twice and show modules that would be marked touched (no mutations)';

    public function handle(EmployeeDataSnapshotService $snapshotService, EmployeeDataChangeLogService $logService)
    {
        $employeeId = (int) $this->argument('employeeId');
        $employee = Employee::find($employeeId);

        if ($employee === null) {
            $this->error("Employee #{$employeeId} not found.");

            return Command::FAILURE;
        }

        $before = $snapshotService->capture($employeeId);
        $after = $snapshotService->capture($employeeId);
        $touched = $logService->deriveModulesTouched($before, $after);

        $this->info("Dry-run for employee #{$employeeId} ({$employee->name})");
        $this->line('Modules touched (expect empty when identical): ' . (empty($touched) ? '(none)' : implode(', ', $touched)));
        $this->line('Audit enabled for company ' . $employee->created_by . ': ' . ($logService->isEnabled((int) $employee->created_by) ? 'yes' : 'no'));

        return Command::SUCCESS;
    }
}
