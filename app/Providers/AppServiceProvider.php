<?php

namespace App\Providers;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        /** 
         * Rate limiters
         */
        RateLimiter::for("api", function (Request $request) {
            return Limit::perMinute(30)
                ->by($request->user()->id ?: $request->ip());
        });

        /**
         * grant access to all permissions for super-admin role
        */
        Gate::before(function (User $user) {
            return $user->hasRole(UserRoles::SuperAdmin);
        });

        /**
         * defining gate to authorize controlling critical 
         * actions in AuthorizationController.
         */
        Gate::define("roles-permissions-crud", function (User $user) {
            return $user->hasRole(UserRoles::ProgramManager);
        });
    }
}
