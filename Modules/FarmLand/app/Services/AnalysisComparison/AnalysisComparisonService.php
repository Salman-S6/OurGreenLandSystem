<?php

namespace Modules\FarmLand\Services\AnalysisComparison;

use Modules\CropManagement\Models\Crop;
use Modules\FarmLand\Enums\AnalysisType;
use Modules\FarmLand\Models\SoilAnalysis;
use Modules\FarmLand\Models\WaterAnalysis;
use Modules\CropManagement\Models\CropPlan;
use Modules\FarmLand\Models\IdealAnalysisValue;

class AnalysisComparisonService
{
    /**
     * Summary of results.
     * @var array
     */
    private array $results = [];

    /**
     * Summary of ideal.
     * @var
     */
    private ?IdealAnalysisValue $ideal = null;

    /**
     * Compare An Analysis Model With Its Ideal Values.
     *
     * @param SoilAnalysis|WaterAnalysis $analysis
     * @return array
     */
    public function compare(SoilAnalysis|WaterAnalysis $analysis): array
    {
        $cropPlan = CropPlan::where('land_id', $analysis->land_id)
            ->whereDate('planned_planting_date', '<=', $analysis->sample_date)
            ->whereDate('planned_harvest_date', '>=', $analysis->sample_date)
            ->first();

        if (!$cropPlan) {
            return ['status' => 'no_crop_plan', 'message' => 'No active crop plan found.'];
        }

        $analysisType = $analysis instanceof SoilAnalysis ? AnalysisType::soil : AnalysisType::water;
        $this->ideal = IdealAnalysisValue::where('crop_id', $cropPlan->crop_id)->where('type', $analysisType)->first();

        if (!$this->ideal) {
            return ['status' => 'missing_ideal_values', 'message' => "No ideal values for this crop.", 'crop_id' => $cropPlan->crop_id];
        }

        $this->comparePH($analysis->ph_level);
        $this->compareSalinity($analysis->salinity_level);
        if ($analysisType === AnalysisType::soil) {
            $this->compareFertility($analysis->fertility_level);
        }

        $overallGrade = $this->calculateOverallGrade();

        $suggestedCrops = $this->suggestSuitableCrops($analysis);

        return [
            'details' => $this->results,
            'overall_grade' => $overallGrade,
            'suggested_crops' => $suggestedCrops,
        ];
    }

    /**
     * Summary of comparePH.
     *
     * @param float $value
     * @return void
     */
    private function comparePH(float $value): void
    {
        if ($value < $this->ideal->ph_min) {
            $this->results['ph'] = ['status' => 'low', 'recommendation' => 'pH is too acidic. Consider adding lime.'];
        } elseif ($value > $this->ideal->ph_max) {
            $this->results['ph'] = ['status' => 'high', 'recommendation' => 'pH is too alkaline. Consider adding sulfur or organic matter.'];
        } else {
            $this->results['ph'] = ['status' => 'ideal', 'recommendation' => 'pH level is optimal.'];
        }
    }

    /**
     * Summary of compareSalinity.
     *
     * @param float $value
     * @return void
     */
    private function compareSalinity(float $value): void
    {
        if ($value > $this->ideal->salinity_max) {
            $this->results['salinity'] = ['status' => 'high', 'recommendation' => 'Salinity is too high. Ensure proper drainage and use low-salinity water.'];
        } else {
            $this->results['salinity'] = ['status' => 'ideal', 'recommendation' => 'Salinity level is optimal.'];
        }
    }

    /**
     * Summary of compareFertility.
     *
     * @param mixed $value
     * @return void
     */
    private function compareFertility($value): void
    {
        $this->results['fertility'] = ($value === $this->ideal->fertility_level)
            ? ['status' => 'ideal', 'recommendation' => 'Fertility level is as expected.']
            : ['status' => 'mismatch', 'recommendation' => "Fertility is not ideal. Expected {$this->ideal->fertility_level->value}, but got {$value->value}."];
    }

    /**
     * Summary of calculateOverallGrade.
     *
     * @return string
     */
    private function calculateOverallGrade(): string
    {
        $score = 0;
        $total = count($this->results);
        foreach ($this->results as $result) {
            if ($result['status'] === 'ideal') {
                $score += 2;
            } elseif ($result['status'] !== 'ideal') {
                $score += 1;
            }
        }
        $percentage = ($score / ($total * 2)) * 100;
        if ($percentage >= 80)
            return 'Excellent';
        if ($percentage >= 50)
            return 'Good';
        return 'Needs Improvement';
    }

    /**
     * Summary of suggestSuitableCrops.
     *
     * @param \Modules\FarmLand\Models\SoilAnalysis|\Modules\FarmLand\Models\WaterAnalysis $analysis
     * @return array
     */
    private function suggestSuitableCrops(SoilAnalysis|WaterAnalysis $analysis): array
    {
        $matchingIdeals = IdealAnalysisValue::where('type', $analysis instanceof SoilAnalysis ? 'soil' : 'water')
            ->where('ph_min', '<=', $analysis->ph_level)
            ->where('ph_max', '>=', $analysis->ph_level)
            ->where('salinity_max', '>=', $analysis->salinity_level)
            ->pluck('crop_id');

        return Crop::whereIn('id', $matchingIdeals)->get(['id', 'name'])->toArray();
    }
}
