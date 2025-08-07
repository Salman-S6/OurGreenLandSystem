<?php

namespace Modules\Reporting\Services;

use Modules\CropManagement\Models\ProductionEstimation;
use Modules\FarmLand\Models\Land;

class SuperAdminReportsService
{
    /**
     * Summary of getProductionOverview
     * @return \Illuminate\Database\Eloquent\Collection<int, array{crop_name: mixed, land_id: mixed, total_produced: mixed>|\Illuminate\Support\Collection<int, array{crop_name: mixed, land_id: mixed, total_produced: mixed}>}
     */
    public function getProductionOverview()
    {
        
        return ProductionEstimation::with(['cropPlan' => ['crop', 'land']])
        ->whereNotNull('actual_quantity')
            ->selectRaw('crop_plan_id, SUM(actual_quantity) as total_produced')
            ->groupBy('crop_plan_id')
            ->get()
            ->map(function ($estimation)  {
                return [
                    'crop_name' => optional($estimation->cropPlan->crop)->getTranslations('name') ?? 'N/A',
                    'land_id' => $estimation->cropPlan->land->id ?? 'N/A',
                    'total_produced' => $estimation->total_produced,
                ];
            });
    }


    /**
     * Summary of getRehabilitatedAreasSummary
     */
    public function getRehabilitatedAreasSummary()
    {
        return Land::whereHas('rehabilitations')
            ->selectRaw('COUNT(*) as count, SUM(area) as total_area')
            ->first();
    }


    /**
     * Summary of getFinishedProductionEstimationsWithGaps
     * @return \Illuminate\Support\Collection<int, array{actual_quantity: float, crop_name: mixed, expected_quantity: float, gap_percentage: float|null, land_id: mixed, notes: mixed, status: string>}
     */
    public function getFinishedProductionEstimationsWithGaps()
    {
        return ProductionEstimation::with(['cropPlan' => ['crop', 'land']])
            ->whereNotNull('actual_quantity')  
            ->get()
            ->map(function ($estimation)  {
                $expected = $estimation->expected_quantity ?? 0;
                $actual = $estimation->actual_quantity ?? 0;

                $gapExists = $expected > 0 && ($actual / $expected) < 0.7; 

                return [
                    'crop_name' => optional($estimation->cropPlan->crop)->getTranslations('name') ?? 'N/A',
                    'land_id' => $estimation->cropPlan->land?->id ?? 'N/A',
                    'expected_quantity' => round($expected, 2),
                    'actual_quantity' => round($actual, 2),
                    'gap_percentage' => $expected > 0
                        ? round((1 - ($actual / $expected)) * 100, 1)
                        : null,
                    'status' => $gapExists ? 'Gap' : 'Sufficient',
                    'notes' => $estimation->notes,
                ];
            })
            ->filter(fn($item) => $item['status'] === 'Gap') 
            ->values();
    }
}
