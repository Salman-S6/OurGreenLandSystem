<?php

namespace Modules\CropManagement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CropManagement\Events\CropPlanCreated;
use Modules\CropManagement\Events\CropPlanDeleted;
use Modules\CropManagement\Events\CropPlanUpdated;
use Modules\CropManagement\Listeners\CropPlanCreatedListener;
use Modules\CropManagement\Listeners\CropPlanDeletedListener;
use Modules\CropManagement\Listeners\CropPlanUpdatedListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */

    protected $listen = [
        CropPlanCreated::class => [
            CropPlanCreatedListener::class,
        ],

        CropPlanUpdated::class => [
            CropPlanUpdatedListener::class,
        ],



    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
