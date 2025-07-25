<?php

namespace Modules\FarmLand\Services\SoilAnalysis;

use Modules\FarmLand\Models\SoilAnalysis;
use Modules\FarmLand\Services\AnalysisComparison\AnalysisComparisonService;

class SoilAnalysisService
{
    public function __construct(protected AnalysisComparisonService $analysisComparison)
    {
    }

    /**
     * Summary of getAll.
     *
     * @return array
     */
    public function getAll(): array
    {
        $data = SoilAnalysis::with(['land', 'performer'])->paginate(perPage: 15);
        return [$data];
    }

    /**
     * Summary of getSoilAnalysis.
     *
     * @param mixed $soilAnalysis
     * @return array
     */
    public function getSoilAnalysis($soilAnalysis): array
    {
        $data = $soilAnalysis->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of store.
     *
     * @param mixed $request
     * @return array
     */
    public function store($request): array
    {
        $data = SoilAnalysis::create($request->validated());
        $comparisonResult = $this->analysisComparison->compare($data);
        if (!isset($comparisonResult['error'])) {
            $data['comparison_result'] = $comparisonResult;
        }
        $data->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of update.
     *
     * @param mixed $request
     * @param mixed $soilAnalysis
     * @return array
     */
    public function update($request, $soilAnalysis): array
    {
        $soilAnalysis->update($request->validated());
        $data = $soilAnalysis;
        $comparisonResult = $this->analysisComparison->compare($data);
        if (!isset($comparisonResult['error'])) {
            $data['comparison_result'] = $comparisonResult;
        }
        $data = $soilAnalysis->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of destroy.
     *
     * @param mixed $soilAnalysis
     * @return array
     */
    public function destroy($soilAnalysis): mixed
    {
        return $soilAnalysis->delete();
    }
}
