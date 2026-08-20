<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CronController;

class AutoLeaveDeductionCommand extends Command
{
     protected $signature = 'cron:auto-leave-deduction';

     protected $description = 'Run auto leave deduction cron';

     public function handle()
     {
          // Call controller method directly
          app(CronController::class)->auto_leave_deduction();

          $this->info('Auto leave deduction executed successfully.');

          return Command::SUCCESS;
     }
}
