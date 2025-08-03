<?php

namespace App\Console\Commands;

use App\Helpers\NotifyHelper;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Modules\CropManagement\Models\CropPlan;

class NotifyAboutUpcomingPlanting extends Command
{
    protected $signature = 'notify:upcoming-planting';
    protected $description = 'Notify farmers or engineers about crop plans that are about to be planted in 3 days';

    public function handle(): void
    {
        $targetDate = Carbon::now()->addDays(3)->toDateString();

        $plans = CropPlan::with('land.farmer')
            ->whereDate('planned_planting_date', $targetDate)
            ->get();

        foreach ($plans as $plan) {
            $user = $plan->land->farmer;
            $cropNameAr = $plan->crop->getTranslation('name', 'ar');
            $cropNameEn = $plan->crop->getTranslation('name', 'en');
            if ($user) {
                NotifyHelper::send($user, [
                    'title' => 'Upcoming Planting Date',
                    'message' => "The planting date for the crop ({$cropNameEn}) / ({$cropNameAr}) on Land #{$plan->land->id} is approaching.",
                    'type' => 'info',
                ], ['mail']);
            }
        }

        $this->info("Notifications sent successfully.");
    }


    /**
     * Summary of schedule
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule){
        $schedule->dailyAt('12:00');
    }
}
