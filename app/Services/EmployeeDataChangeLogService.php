<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeDataChangeLog;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class EmployeeDataChangeLogService
{
    public const SETTING_KEY = 'employee_audit_log_enabled';

    public const MODULE_LABELS = [
        'basic_details' => 'Basic Details',
        'job_org' => 'Job / Organization',
        'salary_ctc' => 'Salary / CTC',
        'bank_tax' => 'Bank / Tax',
        'statutory' => 'Statutory',
        'earnings' => 'Earnings / Allowances',
        'deductions' => 'Deductions',
        'working_shifts' => 'Working Shifts',
        'weekoffs' => 'Week-offs',
        'login_access' => 'Login / Access',
        'experience' => 'Experience',
        'education' => 'Education',
        'dependents' => 'Dependents',
        'documents' => 'Documents',
        'user_account' => 'User Account',
    ];

    /**
     * Whether audit logging is enabled for a company tenant.
     * Default: on when setting is missing.
     */
    public function isEnabled(?int $companyId = null): bool
    {
        $companyId = $companyId ?? (Auth::check() ? (int) Auth::user()->creatorId() : 0);
        if ($companyId <= 0) {
            return false;
        }

        $row = DB::table('settings')
            ->where('created_by', $companyId)
            ->where('name', self::SETTING_KEY)
            ->first();

        if ($row === null) {
            return true;
        }

        $value = strtolower(trim((string) $row->value));

        return in_array($value, ['on', '1', 'true', 'yes'], true);
    }

    /**
     * Persist one audit row. Never throws to callers — failures are logged.
     */
    public function record(int $employeeId, array $before, array $after, array $context = []): void
    {
        try {
            $employee = Employee::find($employeeId);
            if ($employee === null) {
                return;
            }

            $companyId = (int) ($context['company_id'] ?? $employee->created_by ?? 0);
            if ($companyId <= 0 && Auth::check()) {
                $companyId = (int) Auth::user()->creatorId();
            }

            if (!$this->isEnabled($companyId)) {
                return;
            }

            if (empty($before) && empty($after)) {
                return;
            }

            $modulesTouched = $this->deriveModulesTouched($before, $after);

            $changedBy = Auth::id();
            $actorType = Auth::check() ? (string) Auth::user()->type : ($context['actor_type'] ?? null);

            $requestMeta = array_filter([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'profile_data_type' => $context['profile_data_type'] ?? null,
                'url' => Request::path(),
            ], function ($v) {
                return $v !== null && $v !== '';
            });

            EmployeeDataChangeLog::create([
                'employee_id' => $employeeId,
                'company_id' => $companyId,
                'changed_by_user_id' => $changedBy,
                'actor_type' => $actorType,
                'source' => $context['source'] ?? 'web_admin',
                'action' => $context['action'] ?? 'update',
                'snapshot_before' => $before,
                'snapshot_after' => $after,
                'modules_touched' => $modulesTouched,
                'request_meta' => $requestMeta,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Employee data change audit failed: ' . $e->getMessage(), [
                'employee_id' => $employeeId,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Capture before/after and record when enabled. Safe wrapper for controllers.
     */
    public function recordFromEmployee(int $employeeId, array $before, array $context = []): void
    {
        try {
            $companyId = (int) ($context['company_id'] ?? 0);
            if ($companyId <= 0) {
                $employee = Employee::find($employeeId);
                $companyId = (int) ($employee->created_by ?? 0);
            }

            if (!$this->isEnabled($companyId)) {
                return;
            }

            $snapshotService = app(EmployeeDataSnapshotService::class);
            $after = $snapshotService->capture($employeeId);
            $this->record($employeeId, $before, $after, $context);
        } catch (\Throwable $e) {
            Log::error('Employee data change audit capture failed: ' . $e->getMessage(), [
                'employee_id' => $employeeId,
            ]);
        }
    }

    /**
     * Deep-compare top-level modules and return keys that differ.
     */
    public function deriveModulesTouched(array $before, array $after): array
    {
        $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
        $touched = [];

        foreach ($keys as $key) {
            $left = $before[$key] ?? null;
            $right = $after[$key] ?? null;
            if ($this->normalizeForCompare($left) !== $this->normalizeForCompare($right)) {
                $touched[] = $key;
            }
        }

        return $touched;
    }

    protected function normalizeForCompare($value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    public static function moduleLabel(string $key): string
    {
        return self::MODULE_LABELS[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    /**
     * Ensure default setting row exists for a company (on).
     */
    public function ensureDefaultSetting(int $companyId): void
    {
        $exists = DB::table('settings')
            ->where('created_by', $companyId)
            ->where('name', self::SETTING_KEY)
            ->exists();

        if (!$exists) {
            DB::insert(
                'insert into settings (`value`, `name`, `created_by`, `created_at`, `updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
                ['on', self::SETTING_KEY, $companyId, now(), now()]
            );
        }
    }
}
