<?php

namespace Modules\FarmLand\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\FarmLand\Models\Land;

class LandObserver
{
    /**
     * Handle the Land "saved" event (store & update).
     */
    public function saved(Land $land): void
    {
        // 1) حدّد المفاتيح اللي لازم تحدث
        $showKey       = "land_show_{$land->id}";
        $allKey        = 'lands_all';
        $prioritizedKey= 'lands_prioritized';

        // 2) مسح الكاش القديم
        Cache::forget($showKey);
        Cache::forget($allKey);
        Cache::forget($prioritizedKey);

        // 3) إعادة تخزين القيمة الجديدة فوراً
        Cache::put($showKey, $land->fresh(), now()->addMinutes(10));
    }

    /**
     * Handle the Land "deleted" event.
     */
    public function deleted(Land $land): void
    {
        $showKey       = "land_show_{$land->id}";
        $allKey        = 'lands_all';
        $prioritizedKey= 'lands_prioritized';

        Cache::forget($showKey);
        Cache::forget($allKey);
        Cache::forget($prioritizedKey);
    }
}
