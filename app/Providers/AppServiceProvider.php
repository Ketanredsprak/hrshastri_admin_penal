<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
     /**
      * Register any application services.
      *
      * @return void
      */
     public function register()
     {
          //
     }

     /**
      * Bootstrap any application services.
      *
      * @return void
      */
     public function boot()
     {
          Schema::defaultStringLength(191);
          \DB::disableQueryLog();
          

          

          // Define a global rate limit of 100 requests per minute
          // RateLimiter::for('global', function ($request)
          // {
          //      $key = $request->user()?->id ?: $request->ip();
          //      $attempts = RateLimiter::attempts($key);

          //      Log::info('Rate limit attempts for key: ' . $key . ' - ' . $attempts . ' attempts');

          //      return Limit::perMinute(1000)->by($key);
          // });
     }
}
