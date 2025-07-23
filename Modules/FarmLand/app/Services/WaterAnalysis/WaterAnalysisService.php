<?php

namespace Modules\FarmLand\Services\WaterAnalysis;

use Modules\FarmLand\Models\WaterAnalysis;

class WaterAnalysisService
{
    /**
     * Summary of getAll
     * @return array
     */
    public function getAll()
    {
        $data = WaterAnalysis::with(['land', 'performer'])->paginate(perPage: 15);
        return [$data];
    }

    /**
     * Summary of getWaterAnalysis
     * @param mixed $waterAnalysis
     * @return array
     */
    public function getWaterAnalysis($waterAnalysis)
    {
        $data = $waterAnalysis->load(['land', 'performer']);
        return $data;
    }

    /**
     * Summary of store
     * @param mixed $request
     * @return array
     */
    public function store($request)
    {
        $data = WaterAnalysis::create($request->validated());
        $data->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of update
     * @param mixed $request
     * @param mixed $waterAnalysis
     * @return array
     */
    public function update($request, $waterAnalysis)
    {
        $waterAnalysis->update($request->validated());
        $data = $waterAnalysis->load(['land', 'performer']);
        return [$data];
    }

    /**
     * Summary of destroy
     * @param mixed $waterAnalysis
     * @return array
     */
    public function destroy($waterAnalysis)
    {
        return $waterAnalysis->delete();
    }
}
