<?php

namespace Modules\FarmLand\Services\SoilAnalysis;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\FarmLand\Models\SoilAnalysis;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Services\AnalysisComparison\AnalysisComparisonService;

class SoilAnalysisService implements BaseCrudServiceInterface
{
    public function __construct(protected AnalysisComparisonService $comparisonService)
    {
    }

    /**
     * Get all Soil Analysis.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $data = SoilAnalysis::with(['land', 'performer'])->paginate(perPage: 15);
        return $data;
    }

    /**
     * Get a Soil Analysis
     * @param mixed $soilAnalysis
     * @return Model
     */
    public function get($soilAnalysis): Model
    {
        $data = $soilAnalysis->load(['land', 'performer']);
        return $data;
    }

    /**
     * Store Soil Analysis.
     *
     * @param mixed $data
     * @return Model
     */
    public function store($data): Model
    {
        $data['performed_by'] = Auth::id();

        $soilAnalysis = SoilAnalysis::create($data);
        $comparisonResult = $this->comparisonService->compare($soilAnalysis);

        $suggestedRecommendations = [];
        if (!empty($comparisonResult['recommendations'])) {
            $recommendations_en = array_column($comparisonResult['recommendations'], 'en');
            $recommendations_ar = array_column($comparisonResult['recommendations'], 'ar');

            $suggestedRecommendations = [
                'en' => implode(' ', $recommendations_en),
                'ar' => implode(' ', $recommendations_ar),
            ];
        }

        $soilAnalysis['comparison_result'] = $comparisonResult['details'] ?? [];
        $soilAnalysis['suggested_crops'] = $comparisonResult['suggested_crops'] ?? [];
        $soilAnalysis['suggested_recommendations'] = $suggestedRecommendations;
        $soilAnalysis->load(['land', 'performer']);

        return $soilAnalysis;
    }

    /**
     * Update Soil Analysis.
     *
     * @param mixed $data
     * @param mixed $soilAnalysis
     * @return Model
     */
    public function update($data, $soilAnalysis): Model
    {
        $data['performed_by'] = Auth::id();
        $soilAnalysis->update($data);

        $comparisonResult = $this->comparisonService->compare($soilAnalysis);
        $suggestedRecommendations = [];
        if (!empty($comparisonResult['recommendations'])) {
            $recommendations_en = array_column($comparisonResult['recommendations'], 'en');
            $recommendations_ar = array_column($comparisonResult['recommendations'], 'ar');

            $suggestedRecommendations = [
                'en' => implode(' ', $recommendations_en),
                'ar' => implode(' ', $recommendations_ar),
            ];
        }

        $soilAnalysis['comparison_result'] = $comparisonResult['details'] ?? [];
        $soilAnalysis['suggested_crops'] = $comparisonResult['suggested_crops'] ?? [];
        $soilAnalysis['suggested_recommendations'] = $suggestedRecommendations;
        $soilAnalysis->load(['land', 'performer']);

        return $soilAnalysis;
    }

    /**
     * Delete a Soil Analysis.
     *
     * @param mixed $soilAnalysis
     * @return bool
     */
    public function destroy($soilAnalysis): bool
    {
        return $soilAnalysis->delete();
    }
}
