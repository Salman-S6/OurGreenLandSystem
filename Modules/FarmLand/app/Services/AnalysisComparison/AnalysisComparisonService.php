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
     * Summary of ideal
     * @var
     */
    private ?IdealAnalysisValue $ideal = null;

    /**
     * Summary of rules
     * @var array
     */
    private array $rules = [];

    /**
     * Summary of __construct.
     *
     */
    public function __construct()
    {
        $this->initializeRules();
    }

    /**
     * Initializes the rules engine with all conditions and recommendations.
     *
     * @return void
     */
    private function initializeRules(): void
    {
        $this->rules = [
            'ph' => [
                'field' => 'ph_level',
                'evaluator' => function ($value, $ideal) {
                    if ($value < $ideal->ph_min) {
                        return [
                            'status' => 'low',
                            'recommendation' => ['en' => 'pH is too acidic. Consider adding lime.', 'ar' => 'درجة الحموضة حمضية جداً. ينصح بإضافة الجير.']
                        ];
                    }
                    if ($value > $ideal->ph_max) {
                        return [
                            'status' => 'high',
                            'recommendation' => ['en' => 'pH is too alkaline. Consider adding sulfur.', 'ar' => 'درجة الحموضة قلوية جداً. ينصح بإضافة الكبريت.']
                        ];
                    }
                    return [
                        'status' => 'ideal',
                        'recommendation' => ['en' => 'pH level is optimal.', 'ar' => 'مستوى درجة الحموضة مثالي.']
                    ];
                }
            ],
            'salinity' => [
                'field' => 'salinity_level',
                'evaluator' => function ($value, $ideal) {
                    if ($value > $ideal->salinity_max) {
                        return [
                            'status' => 'high',
                            'recommendation' => ['en' => 'Salinity is too high. Ensure proper drainage.', 'ar' => 'نسبة الملوحة مرتفعة جداً. تأكد من وجود تصريف جيد.']
                        ];
                    }
                    if ($value < $ideal->salinity_min) {
                        return [
                            'status' => 'low',
                            'recommendation' => [
                                'en' => 'Salinity level is very low, which is excellent for most crops.',
                                'ar' => 'مستوى الملوحة منخفض جداً، وهو ممتاز لمعظم المحاصيل.'
                            ]
                        ];
                    }
                    return [
                        'status' => 'ideal',
                        'recommendation' => ['en' => 'Salinity level is optimal.', 'ar' => 'مستوى الملوحة مثالي.']
                    ];
                }
            ],
        ];
    }

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

        $analysisType = $analysis instanceof SoilAnalysis ? AnalysisType::Soil : AnalysisType::Water;
        $this->ideal = IdealAnalysisValue::where('crop_id', $cropPlan->crop_id)->where('type', $analysisType)->first();

        if (!$this->ideal) {
            return ['status' => 'missing_ideal_values', 'message' => "No ideal values for this crop.", 'crop_id' => $cropPlan->crop_id];
        }

        $comparisonDetails = [];
        $recommendationsToStore = [];

        foreach ($this->rules as $metric => $rule) {
            if (!isset($analysis->{$rule['field']}))
                continue;

            $value = $analysis->{$rule['field']};
            $result = $rule['evaluator']($value, $this->ideal);

            $comparisonDetails[$metric] = [
                'status' => $result['status'],
                'recommendation' => $result['recommendation'][app()->getLocale()] ?? $result['recommendation']['en'],
            ];

            if ($result['status'] !== 'ideal') {
                $recommendationsToStore[] = $result['recommendation'];
            }
        }

        $suggestedCrops = $this->suggestSuitableCrops($analysis);

        return [
            'details' => $comparisonDetails,
            'recommendations_to_store' => $recommendationsToStore,
            'suggested_crops' => $suggestedCrops,
        ];
    }

    /**
     * Summary of suggestSuitableCrops.
     *
     * @param \Modules\FarmLand\Models\SoilAnalysis|\Modules\FarmLand\Models\WaterAnalysis $analysis
     * @return array
     */
    private function suggestSuitableCrops(SoilAnalysis|WaterAnalysis $analysis): array
    {
        $analysisType = $analysis instanceof SoilAnalysis ? AnalysisType::Soil : AnalysisType::Water;

        $matchingIdeals = IdealAnalysisValue::where('type', $analysisType)
            ->where('ph_min', '<=', $analysis->ph_level)
            ->where('ph_max', '>=', $analysis->ph_level)
            ->where('salinity_max', '>=', $analysis->salinity_level)
            ->pluck('crop_id');

        return Crop::whereIn('id', $matchingIdeals)->get(['id', 'name'])->toArray();
    }
}
