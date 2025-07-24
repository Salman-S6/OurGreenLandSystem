<?php

namespace Modules\CropManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\CropManagement\Emails\CropPlanDeletedMail;
use Modules\CropManagement\Events\CropPlanDeleted;
use Modules\CropManagement\Jobs\CropPlanDeletedjob;

class CropPlanDeletedListener implements ShouldQueue
{
 
   
    /**
     * Handle the event.
     */
    public function handle( CropPlanDeleted $event): void {
           $emails = [
            $event->cropPlan->planner->email,
            $event->cropPlan->land->user->email,
            $event->cropPlan->land->farmer->email,
        ];

        foreach($emails as $email){
            Mail::to($email)->send(new CropPlanDeletedMail($event->cropPlan));
        }
    }
}
