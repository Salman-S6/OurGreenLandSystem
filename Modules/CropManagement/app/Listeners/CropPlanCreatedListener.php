<?php

namespace Modules\CropManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\CropManagement\Emails\CropPlanCreatedMail;
use Modules\CropManagement\Events\CropPlanCreated;

class CropPlanCreatedListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
   public function handle(CropPlanCreated $event): void
{
    $cropPlan = $event->cropPlan;

    $cropPlan->loadMissing(['planner', 'land.user', 'land.farmer', 'crop']);
    
    $emails = [
        optional($cropPlan->planner)->email,
        optional($cropPlan->land->user)->email,
        optional($cropPlan->land->land->farmer)->email,
    ];

    foreach ($emails as $email) {
        try {
            if ($email) {
                Mail::to($email)->send(new CropPlanCreatedMail($cropPlan));
            }
        } catch (\Throwable $e) {
              Log::error("Failed to send crop plan email to [$email]: " . $e->getMessage(), [
        'trace' => $e->getTraceAsString()
    ]);
        }
    }
}

}
