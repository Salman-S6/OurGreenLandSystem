<?php

namespace Modules\CropManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\CropManagement\Emails\CropPlanDeletedMail;
use Modules\CropManagement\Events\CropPlanDeleted;
use Modules\CropManagement\Jobs\CropPlanDeletedjob;

class CropPlanDeletedListener implements ShouldQueue
{


    /**
     * Handle the event.
     */
    public function handle(CropPlanDeleted $event): void
    {

        $cropPlan = $event->cropPlan;

        $cropPlan->loadMissing(['planner', 'land.user', 'land.farmer', 'crop']);
        $emails = [
            optional($cropPlan->planner)->email,
            optional($cropPlan->land->user)->email,
            optional($cropPlan->land->farmer)->email,
        ];

        foreach ($emails as $email) {
            try {
                if ($email) {
                    Mail::to($email)->send(new CropPlanDeletedMail($cropPlan));
                }
            } catch (\Throwable $e) {
                Log::error("Failed to send crop plan email to [$email]: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }
}
