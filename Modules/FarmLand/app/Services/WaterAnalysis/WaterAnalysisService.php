<?php

namespace Modules\FarmLand\Services\WaterAnalysis;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Modules\FarmLand\Models\WaterAnalysis;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Services\AnalysisComparison\AnalysisComparisonService;

class WaterAnalysisService implements BaseCrudServiceInterface
{
    public function __construct(protected AnalysisComparisonService $comparisonService)
    {
    }

    /**
     * Get all Water Analysis.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $cacheKey = "water_analyses_all";
        return Cache::remember($cacheKey, now()->addHours(1), function () {
            return WaterAnalysis::with(['land', 'performer'])->paginate(15);
        });
    }

    /**
     * Get a Water Analysis
     * @param mixed $waterAnalysis
     * @return Model
     */
    public function get($waterAnalysis): Model
    {
        $cacheKey = "water_analysis_{$waterAnalysis->id}";
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($waterAnalysis) {
            return $waterAnalysis->load(['land', 'performer']);
        });
    }

    /**
     * Store Water Analysis.
     *
     * @param mixed $data
     * @return Model
     */
    public function store($data): Model
    {
        $data['performed_by'] = Auth::id();

        $waterAnalysis = WaterAnalysis::create($data);
        $comparisonResult = $this->comparisonService->compare($waterAnalysis);

        $suggestedRecommendations = [];
        if (!empty($comparisonResult['recommendations_to_store'])) {
            $recommendations_en = array_column($comparisonResult['recommendations_to_store'], 'en');
            $recommendations_ar = array_column($comparisonResult['recommendations_to_store'], 'ar');

            $suggestedRecommendations = [
                'en' => implode(' ', $recommendations_en),
                'ar' => implode(' ', $recommendations_ar),
            ];
        }

        $waterAnalysis['comparison_result'] = $comparisonResult['details'] ?? [];
        $waterAnalysis['suggested_crops'] = $comparisonResult['suggested_crops'] ?? [];
        $waterAnalysis['suggested_recommendations'] = $suggestedRecommendations;
        $data = $waterAnalysis->load(['land', 'performer']);
        Cache::forget("water_analyses_all");
        Cache::forget("water_analysis_{$waterAnalysis->id}");

        return $data;
    }

    /**
     * Update Water Analysis.
     *
     * @param mixed $data
     * @param mixed $waterAnalysis
     * @return Model
     */
    public function update($data, $waterAnalysis): Model
    {
        $data['performed_by'] = Auth::id();

        $waterAnalysis->update($data);
        $data = $waterAnalysis;
        $comparisonResult = $this->comparisonService->compare($data);

        $suggestedRecommendations = [];
        if (!empty($comparisonResult['recommendations_to_store'])) {
            $recommendations_en = array_column($comparisonResult['recommendations_to_store'], 'en');
            $recommendations_ar = array_column($comparisonResult['recommendations_to_store'], 'ar');

            $suggestedRecommendations = [
                'en' => implode(' ', $recommendations_en),
                'ar' => implode(' ', $recommendations_ar),
            ];
        }

        $data['comparison_result'] = $comparisonResult['details'] ?? [];
        $data['suggested_crops'] = $comparisonResult['suggested_crops'] ?? [];
        $data['suggested_recommendations'] = $suggestedRecommendations;
        $data->load(['land', 'performer']);
        Cache::forget("water_analyses_all");
        Cache::forget("water_analysis_{$waterAnalysis->id}");

        return $data;
    }

    /**
     * Delete a Water Analysis.
     *
     * @param mixed $waterAnalysis
     * @return bool
     */
    public function destroy($waterAnalysis): bool
    {
        Cache::forget("water_analyses_all");
        Cache::forget("water_analysis_{$waterAnalysis->id}");
        return $waterAnalysis->delete();
    }
}
