<?php

namespace Modules\Reporting\Services;

use Illuminate\Support\Facades\Auth;
use Modules\CropManagement\Models\PestDiseaseCase;
use Modules\FarmLand\Models\SoilAnalysis;
use Modules\FarmLand\Models\WaterAnalysis;

class EngineerReportsService
{
    /**
     * Summary of getSoilAnalyses
     * @return \Illuminate\Database\Eloquent\Collection<int, SoilAnalysis>
     */
    public function getSoilAnalyses()
    {
        return SoilAnalysis::with('land')
            ->where('performed_by', Auth::id())
            ->latest('created_at')
            ->take(10)
            ->get();
    }

    /**
     * Summary of getWaterAnalyses
     * @return \Illuminate\Database\Eloquent\Collection<int, WaterAnalysis>
     */
    public function getWaterAnalyses()
    {
        return WaterAnalysis::with('land')
            ->where('performed_by', Auth::id())
            ->latest('created_at')
            ->take(10)
            ->get();
    }

    /**
     * Summary of getPestDiseaseCases
     * @return \Illuminate\Database\Eloquent\Collection<int, PestDiseaseCase>
     */
    public function getPestDiseaseCases()
    {
        return PestDiseaseCase::with([
            'cropPlan.crop',
            'cropPlan.land',
            'cropPlan.planner',
        ])
            ->whereHas('cropPlan', function ($query) {
                $query->where('planned_by', Auth::id());
            })
            ->latest('created_at')
            ->take(10)
            ->get();
    }
}
