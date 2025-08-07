<?php

namespace Modules\Reporting\Services;

use Modules\FarmLand\Models\Land;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\CropManagement\Models\BestAgriculturalPractice;
use Modules\CropManagement\Models\CropPlan;
use Modules\FarmLand\Models\Soil;

class FarmerReportsService
{
    public function getFarmerLandsSummary(): array
    {
        //عدد الاراضي المملوكه 
        // المساحة الإجمالية.
        // نوع الترب التي لديه

        $userId = Auth::id();
        $lands = Land::select('id', 'area', 'soil_type_id')
            ->where('farmer_id', $userId)
            ->get();
        $landsCount = $lands->count();
        $totalArea = $lands->sum('area');
        $mostCommonSoilTypeId = $lands
            ->groupBy('soil_type_id')
            ->sortByDesc(fn($group) => $group->count())
            ->keys()
            ->first();
        $soilName = null;
        if ($mostCommonSoilTypeId) {
            $soil = Soil::find($mostCommonSoilTypeId);
            if ($soil) {
                $soilName = $soil->getTranslation('name', App::getLocale());
            }
        }

        return [
            'lands_count' => $landsCount,
            'total_area' => $totalArea,
            'most_common_soil_type' => $soilName,
        ];
    }

    public function getLatestCropPlans(): \Illuminate\Support\Collection
    {
        /*
          جلب آخر خطة زراعية مخصصة لكل أرض. 
          تحوي جميع معلومات الخطه بالكامل         
        */

        $userId = Auth::id();
        $landIds = Land::where('farmer_id', $userId)->pluck('id');
        $latestPlans = CropPlan::with(['crop', 'land'])
            ->whereIn('land_id', $landIds)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('land_id')
            ->map(fn($group) => $group->first())
            ->values();
        return $latestPlans;
    }

    public function getRecentGuidance(): \Illuminate\Support\Collection
    {
        // الإرشادات الحديثة التي تهم المزارع بناءً على نوع المحاصيل التي يزرعها أو أراضيه.

        $userId = Auth::id();
        return BestAgriculturalPractice::whereHas('growthStage.cropPlan.land', function ($q) use ($userId) {
            $q->where('lands.farmer_id', $userId);
        })
            ->with([
                'growthStage.cropPlan.crop',
                'growthStage.cropPlan.land',
                'expert',
            ])
            ->latest('application_date')
            ->take(10)
            ->get();
    }

    public function getCropPlanStatusStats(): array
    {
        //إحصائية مرئية (Pie/Bar Chart) توضح عدد الخطط الزراعية حسب حالتها (Active, InProgress, Completed...).


        $userId = Auth::id();
        $statusCounts = CropPlan::whereHas('land', function ($q) use ($userId) {
            $q->where('farmer_id', $userId);
        })
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        return [
            'labels' => $statusCounts->keys(),
            'data' => $statusCounts->values(),
        ];
    }
}
