<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // define the gate
        Gate::define('admin', function($user){
            // name of gate is 'admin', function($user) means the LOGGED IN user
            return $user->role_id === User::ADMIN_ROLE_ID;
        });
    }
}
