<?php

namespace Modules\Reporting\Providers;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void {
        parent::register();
    }

    public function boot()
    {
        Gate::define(
            'view-farmer-reports',
            function (User $user) {
                if ($user->hasRole(UserRoles::Farmer))
                    return true;

                return false;
            }
        );

        Gate::define(
            'view-engineer-reports',
            function (User $user) {
                if ($user->hasRole(UserRoles::AgriculturalEngineer))
                    return true;
                
                return false;
            }
        );

        Gate::define(
            'view-manager-reports',
            function (User $user) {
                if ($user->hasRole(UserRoles::ProgramManager))
                    return true;
                
                return false;
            }
        );

        Gate::define(
            'view-production-estimation-reports',
            function (User $user) {
                if ($user->hasRole([
                    UserRoles::AgriculturalEngineer,
                    UserRoles::DataAnalyst,
                    UserRoles::FundingAgency,
                    UserRoles::ProgramManager
                ]))
                    return true;

                return false;
            }
        );
    }
}
