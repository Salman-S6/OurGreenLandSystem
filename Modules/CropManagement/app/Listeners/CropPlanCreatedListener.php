<?php

namespace Modules\CropManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\CropManagement\Emails\CropPlanCreatedMail;
use Modules\CropManagement\Events\CropPlanCreated;
use Modules\CropManagement\Jobs\CropPlanCreatedJob;

class CropPlanCreatedListener implements ShouldQueue
{

    /**
     * Handle the event.
     */
    public function handle(CropPlanCreated $event): void
    {
        $emails = [
            $event->cropPlan->planner->email,
            $event->cropPlan->land->user->email,
            $event->cropPlan->land->farmer->email,
        ];

        foreach ($emails as $email) {
            Mail::to($email)->send(new CropPlanCreatedMail($event->cropPlan));
        }
    }
}
