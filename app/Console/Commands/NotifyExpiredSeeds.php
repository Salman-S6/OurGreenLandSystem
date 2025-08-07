<?php

namespace App\Console\Commands;

use App\Enums\UserRoles;
use Illuminate\Console\Command;

use App\Models\User;
use App\Helpers\NotifyHelper;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Modules\CropManagement\Models\CropPlan;

class NotifyExpiredSeeds extends Command
{
    protected $signature = 'notify:expired-seeds';
    protected $description = 'Notify program manager and quality unit about expired seeds';

    public function handle()
    {
        $today = Carbon::today();


        $expiredPlans = CropPlan::whereDate('seed_expiry_date', '<', $today)->get();


        $usersToNotify = User::role([UserRoles::SuperAdmin, UserRoles::AgriculturalEngineer, UserRoles::ProgramManager])->get();

        foreach ($expiredPlans as $plan) {
            $cropNameAr = $plan->crop->getTranslation('name', 'ar');
            $cropNameEn = $plan->crop->getTranslation('name', 'en');
            foreach ($usersToNotify as $user) {
                NotifyHelper::send($user, [
                    'title' => 'Expired Seeds Alert',
                    'message' => "The seeds used in crop ({$cropNameEn}) / ({$cropNameAr})on land #{$plan->land->id} have expired on {$plan->seed_expiry_date->format('Y-m-d')}.",
                    'type' => 'danger',
                ], ['mail']);
            }
        }

        $this->info('Expired seed notifications sent.');

        return 0;
    }

    /**
     * Summary of schedule
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        $schedule->dailyAt('00:00');
    }
}
