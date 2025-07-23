<?php

namespace Modules\FarmLand\Services\SoilAnalyses;

use Modules\FarmLand\Models\SoilAnalysis;

class SoilAnalysisService
{
    /**
     * Summary of getAll
     * @return array
     */
    public function getAll()
    {
        $data = SoilAnalysis::with(['land', 'performer'])->paginate(perPage: 15);
        return [$data];
    }

    /**
     * Summary of getSoilAnalysis
     * @param mixed $soilAnalysis
     * @return array
     */
    public function getSoilAnalysis($soilAnalysis)
    {
        $data = $soilAnalysis->load(['land', 'performer']);
        return $data;
    }

    /**
     * Summary of store
     * @param mixed $request
     * @return array
     */
    public function store($request)
    {
        $data = SoilAnalysis::create($request->validated());
        $data->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of update
     * @param mixed $request
     * @param mixed $soilAnalysis
     * @return array
     */
    public function update($request, $soilAnalysis)
    {
        $soilAnalysis->update($request->validated());
        $data = $soilAnalysis->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of destroy
     * @param mixed $soilAnalysis
     * @return array
     */
    public function destroy($soilAnalysis)
    {
        return $soilAnalysis->delete();
    }
}
