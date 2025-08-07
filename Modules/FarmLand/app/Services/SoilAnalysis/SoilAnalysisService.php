<?php

namespace Modules\FarmLand\Services\SoilAnalysis;

use App\Helpers\NotifyHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $cacheKey = "soil_analyses_all";
        return Cache::remember($cacheKey, now()->addHours(1), function () {
            return SoilAnalysis::with(['land', 'performer'])->paginate(15);
        });
    }

    /**
     * Get a Soil Analysis
     * @param mixed $soilAnalysis
     * @return Model
     */
    public function get($soilAnalysis): Model
    {
        $cacheKey = "soil_analysis_{$soilAnalysis->id}";
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($soilAnalysis) {
            return $soilAnalysis->load(['land', 'performer']);
        });
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
        Cache::forget('soil_analyses_all');
        Cache::forget("soil_analysis_{$soilAnalysis->id}");

        $usersNotified = [$soilAnalysis->performer, $soilAnalysis->land->user, $soilAnalysis->land->farmer];

        $notificationTitle = "New Soil Analysis / تحليل تربة جديد";
        $notificationMessage = "A new soil analysis (ID: {$soilAnalysis->id}) has been created for land #{$soilAnalysis->land->id}.";

        NotifyHelper::send($usersNotified, [
            'title' => $notificationTitle,
            'message' => $notificationMessage,
            'type' => 'success'
        ], ['mail']);

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
        Cache::forget('soil_analyses_all');
        Cache::forget("soil_analysis_{$soilAnalysis->id}");

        $usersNotified = [$soilAnalysis->performer, $soilAnalysis->land->user, $soilAnalysis->land->farmer];

        $notificationTitle = "Soil Analysis has been updated / تم تحديث تحليل التربة";
        $notificationMessage = "A soil analysis (ID: {$soilAnalysis->id}) has been updated for land #{$soilAnalysis->land->id}.";

        NotifyHelper::send($usersNotified, [
            'title' => $notificationTitle,
            'message' => $notificationMessage,
            'type' => 'success'
        ], ['mail']);

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
        Cache::forget('soil_analyses_all');
        Cache::forget("soil_analysis_{$soilAnalysis->id}");
        return $soilAnalysis->delete();
    }
}
