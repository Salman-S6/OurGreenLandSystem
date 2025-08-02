<?php

namespace App\Providers;

use App\Enums\AttachableModels;
use App\Enums\UserRoles;
use App\Models\Attachment;
use App\Models\User;
use App\Observers\AttachmentObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        RateLimiter::for("uploads", function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->user()->id ?: $request->ip());
        });

        /**
         * grant access to all permissions for super-admin role
        */
        Gate::before(function (User $user) {
            return $user->hasRole(UserRoles::SuperAdmin) ? true : null;
        });

        /**
         * defining gate to authorize controlling critical 
         * actions in AuthorizationController.
         */
        Gate::define("roles-permissions-crud", function (User $user) {
            return $user->hasRole(UserRoles::ProgramManager) ? true : null;
        });

        /**
         * explicit route model binding
         */
        Route::bind("attachable", function($value, $route) {
            $attachable_id = $route->parameter("attachable_id");

            $classes = AttachableModels::list();

            if (!array_key_exists($value, $classes))
                throw new NotFoundHttpException();

            try {
                $data = $classes[$value]::findOrFail($attachable_id);
                return $data;
            } catch (NotFoundHttpException $e) {
                throw $e;
            }
        });
    }
}
