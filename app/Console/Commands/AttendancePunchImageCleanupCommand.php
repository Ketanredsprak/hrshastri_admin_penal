<?php

namespace App\Console\Commands;

use App\Services\AttendancePunchImageCleanupService;
use Illuminate\Console\Command;

class AttendancePunchImageCleanupCommand extends Command
{
    protected $signature = 'cron:attendance-punch-image-cleanup {--company= : Optional company user id}';

    protected $description = 'Delete old attendance punch images (S3/public) and clear image columns per company retention settings';

    public function handle(AttendancePunchImageCleanupService $service)
    {
        $companyId = (int) $this->option('company');

        if ($companyId > 0) {
            $result = $service->runForCompany($companyId);
            $this->info(json_encode($result, JSON_PRETTY_PRINT));

            return Command::SUCCESS;
        }

        $summary = $service->runAll();
        $this->info('Companies processed: ' . $summary['companies_processed']);
        $this->info('Companies skipped: ' . $summary['companies_skipped']);
        $this->info('Rows updated: ' . $summary['rows_updated']);
        $this->info('Files deleted: ' . $summary['files_deleted']);

        if (!empty($summary['errors'])) {
            $this->warn('Errors: ' . count($summary['errors']));
        }

        return Command::SUCCESS;
    }
}
