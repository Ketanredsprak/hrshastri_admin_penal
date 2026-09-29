<?php

namespace App\Services;

use App\Models\AttendanceEmployee;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttendancePunchImageCleanupService
{
    public const ENABLED_KEY = 'attendance_punch_image_cleanup_enabled';
    public const RETENTION_KEY = 'attendance_punch_image_retention_days';

    /**
     * Run cleanup for all company tenants that have the feature enabled.
     */
    public function runAll(): array
    {
        $summary = [
            'companies_processed' => 0,
            'companies_skipped' => 0,
            'rows_updated' => 0,
            'files_deleted' => 0,
            'errors' => [],
        ];

        $companies = User::where('type', 'company')->pluck('id');

        foreach ($companies as $companyId) {
            try {
                $result = $this->runForCompany((int) $companyId);
                if (!empty($result['skipped'])) {
                    $summary['companies_skipped']++;
                } else {
                    $summary['companies_processed']++;
                    $summary['rows_updated'] += (int) ($result['rows_updated'] ?? 0);
                    $summary['files_deleted'] += (int) ($result['files_deleted'] ?? 0);
                }
            } catch (Throwable $e) {
                $summary['errors'][] = [
                    'company_id' => $companyId,
                    'message' => $e->getMessage(),
                ];
                Log::channel('attendance_punch_image_cleanup')->error('Company cleanup failed', [
                    'company_id' => $companyId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::channel('attendance_punch_image_cleanup')->info('Cleanup run complete', $summary);

        return $summary;
    }

    /**
     * Clean punch images for one company after reading its settings.
     *
     * Retention example: today 2026-09-22, days=10 → cutoff 2026-09-12,
     * delete images for attendance date <= 2026-09-12 (keeps 13..22).
     */
    public function runForCompany(int $companyId): array
    {
        $settings = $this->loadCompanySettings($companyId);
        $enabled = strtolower(trim((string) ($settings[self::ENABLED_KEY] ?? 'off')));

        if (!in_array($enabled, ['on', '1', 'true', 'yes'], true)) {
            return [
                'skipped' => true,
                'reason' => 'disabled',
                'company_id' => $companyId,
                'rows_updated' => 0,
                'files_deleted' => 0,
            ];
        }

        $days = (int) ($settings[self::RETENTION_KEY] ?? 0);
        if ($days < 1) {
            return [
                'skipped' => true,
                'reason' => 'invalid_retention_days',
                'company_id' => $companyId,
                'rows_updated' => 0,
                'files_deleted' => 0,
            ];
        }

        // Keep last N calendar days including today → remove date <= (today - N)
        $cutoff = Carbon::today('Asia/Kolkata')->subDays($days)->toDateString();

        $employeeIds = Employee::where('created_by', $companyId)->pluck('id');
        if ($employeeIds->isEmpty()) {
            return [
                'skipped' => false,
                'company_id' => $companyId,
                'cutoff' => $cutoff,
                'rows_updated' => 0,
                'files_deleted' => 0,
            ];
        }

        $rowsUpdated = 0;
        $filesDeleted = 0;

        AttendanceEmployee::query()
            ->whereIn('employee_id', $employeeIds)
            ->whereDate('date', '<=', $cutoff)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('image')->where('image', '!=', '');
                })->orWhere(function ($q2) {
                    $q2->whereNotNull('clock_out_image')->where('clock_out_image', '!=', '');
                });
            })
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$rowsUpdated, &$filesDeleted, $companyId, $cutoff) {
                foreach ($rows as $row) {
                    $paths = [];
                    $image = trim((string) ($row->getRawOriginal('image') ?? $row->getAttributes()['image'] ?? ''));
                    $clockOutImage = trim((string) ($row->getRawOriginal('clock_out_image') ?? $row->getAttributes()['clock_out_image'] ?? ''));

                    if ($image !== '') {
                        $paths[] = 'images/clock_in_out/' . $row->employee_id . '/' . $image;
                    }
                    if ($clockOutImage !== '') {
                        $paths[] = 'images/clock_in_out/' . $row->employee_id . '/' . $clockOutImage;
                    }

                    if (!empty($paths) && function_exists('deleteFile')) {
                        $deleteResult = deleteFile($paths);
                        $filesDeleted += (int) ($deleteResult['no_of_files'] ?? count($paths));
                    }

                    DB::table('attendance_employees')
                        ->where('id', $row->id)
                        ->update([
                            'image' => null,
                            'clock_out_image' => null,
                            'updated_at' => now(),
                        ]);

                    $rowsUpdated++;
                }

                Log::channel('attendance_punch_image_cleanup')->info('Chunk cleaned', [
                    'company_id' => $companyId,
                    'cutoff' => $cutoff,
                    'chunk_rows' => $rows->count(),
                ]);
            });

        return [
            'skipped' => false,
            'company_id' => $companyId,
            'cutoff' => $cutoff,
            'retention_days' => $days,
            'rows_updated' => $rowsUpdated,
            'files_deleted' => $filesDeleted,
        ];
    }

    /**
     * Load settings for a company without relying on Auth.
     */
    protected function loadCompanySettings(int $companyId): array
    {
        $rows = DB::table('settings')
            ->where('created_by', $companyId)
            ->whereIn('name', [self::ENABLED_KEY, self::RETENTION_KEY])
            ->pluck('value', 'name')
            ->toArray();

        return $rows;
    }
}
