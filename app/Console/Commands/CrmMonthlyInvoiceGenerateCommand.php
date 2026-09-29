<?php

namespace App\Console\Commands;

use App\Services\CrmInvoiceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CrmMonthlyInvoiceGenerateCommand extends Command
{
    protected $signature = 'crm:invoices-generate-monthly
                            {--month= : Billing month (1-12), defaults to current month}
                            {--year= : Billing year, defaults to current year}
                            {--company= : Optional company user id}
                            {--dry-run : Preview without writing}
                            {--no-email : Skip auto email}';

    protected $description = 'Generate CRM company invoices for active employees (last-of-month billing)';

    public function handle(CrmInvoiceService $service)
    {
        $now = Carbon::now('Asia/Kolkata');
        $month = (int) ($this->option('month') ?: $now->month);
        $year = (int) ($this->option('year') ?: $now->year);
        $companyId = (int) $this->option('company');
        $dryRun = (bool) $this->option('dry-run');
        $sendEmail = !$this->option('no-email');

        if ($month < 1 || $month > 12) {
            $this->error('Invalid month.');
            return Command::FAILURE;
        }

        $this->info(sprintf(
            'CRM invoice generate: %04d-%02d%s%s',
            $year,
            $month,
            $companyId ? " company={$companyId}" : '',
            $dryRun ? ' [DRY RUN]' : ''
        ));

        $summary = $service->generateMonthly(
            $year,
            $month,
            $companyId > 0 ? $companyId : null,
            $dryRun,
            $sendEmail
        );

        $this->info('Companies scanned: ' . $summary['companies_scanned']);
        $this->info('Created: ' . $summary['created']);
        $this->info('Skipped (existing): ' . $summary['skipped_existing']);
        $this->info('Skipped (zero employees): ' . $summary['skipped_zero']);
        $this->info('Emails sent: ' . $summary['emails_sent']);

        if (!empty($summary['errors'])) {
            $this->warn('Errors: ' . count($summary['errors']));
            foreach ($summary['errors'] as $err) {
                $this->line(' - Company ' . ($err['company_id'] ?? '?') . ': ' . ($err['message'] ?? ''));
            }
        }

        return Command::SUCCESS;
    }
}
