<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
     /**
      * Define the application's command schedule.
      *
      * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
      * @return void
      */
     protected function schedule(Schedule $schedule)
     {
          // $schedule->command('inspire')->hourly();

          // Log::info('In kernal Schedule', ['time' => time()]);

          $schedule->command('cron:auto-leave-deduction')
               ->twiceDaily(0, 12)
               ->timezone('Asia/Kolkata')
               ->withoutOverlapping()
               ->runInBackground();

          $schedule->command('cron:attendance-punch-image-cleanup')
               ->dailyAt('01:30')
               ->timezone('Asia/Kolkata')
               ->withoutOverlapping()
               ->runInBackground();

          // CRM company invoices — DISABLED temporarily (manual invoices for custom billing only)
          // Re-enable by setting crm_invoice_settings.auto_generate_enabled = 1 and uncommenting below.
          // $schedule->command('crm:invoices-generate-monthly')
          //      ->dailyAt('23:00')
          //      ->timezone('Asia/Kolkata')
          //      ->when(function () {
          //           return now('Asia/Kolkata')->isLastOfMonth();
          //      })
          //      ->withoutOverlapping()
          //      ->runInBackground();
     }

     /**
      * Register the commands for the application.
      *
      * @return void
      */
     protected function commands()
     {
          $this->load(__DIR__ . '/Commands');

          require base_path('routes/console.php');
     }
}
