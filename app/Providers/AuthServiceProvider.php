<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    // public function boot()
    // {
    //     $this->registerPolicies();
    //     Passport::ignoreRoutes();
    // } commented by niurav for too many request issue
    
    public function boot()
    {
        $this->registerPolicies();
    
        // Prevent Passport from re-running migrations
        Passport::ignoreMigrations();
    
        // Reduce DB memory usage
        \DB::disableQueryLog();
    
        // Force close DB connection after each request
        \DB::disconnect();
    }
        
    
}
