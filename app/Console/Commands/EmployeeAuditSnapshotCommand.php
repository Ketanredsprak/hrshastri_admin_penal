<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\EmployeeDataSnapshotService;
use Illuminate\Console\Command;

class EmployeeAuditSnapshotCommand extends Command
{
    protected $signature = 'employee-audit:snapshot {employeeId : Internal employees.id}';

    protected $description = 'Print a full module-keyed employee data snapshot as JSON (debug/support)';

    public function handle(EmployeeDataSnapshotService $snapshotService)
    {
        $employeeId = (int) $this->argument('employeeId');
        $employee = Employee::find($employeeId);

        if ($employee === null) {
            $this->error("Employee #{$employeeId} not found.");

            return Command::FAILURE;
        }

        $snapshot = $snapshotService->capture($employeeId);
        $this->info("Snapshot for employee #{$employeeId} ({$employee->name}):");
        $this->line(json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
