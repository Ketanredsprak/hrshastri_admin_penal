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
