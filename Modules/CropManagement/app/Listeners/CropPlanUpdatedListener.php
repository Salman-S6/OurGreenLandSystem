<?php

namespace Modules\CropManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\CropManagement\Emails\CropPlanUpdatedMail;
use Modules\CropManagement\Events\CropPlanUpdated;
use Modules\CropManagement\Jobs\CropPlanUpdatedjob;

class CropPlanUpdatedListener implements ShouldQueue
{
    /**
     * Summary of handle
     * @param \Modules\CropManagement\Events\CropPlanUpdated $event
     * @return void
     */
    public function handle(CropPlanUpdated $event): void {
      $emails = [
            $event->cropPlan->planner->email,
            $event->cropPlan->land->user->email,
            $event->cropPlan->land->farmer->email,
        ];
         foreach($emails as $email){
            Mail::to($email)->send(new CropPlanUpdatedMail($event->cropPlan));
        } 
    }
}
