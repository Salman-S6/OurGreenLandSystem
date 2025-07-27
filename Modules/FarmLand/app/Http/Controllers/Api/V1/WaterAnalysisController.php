<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\FarmLand\Http\Requests\WaterAnalysis\StoreWaterAnalysisRequest;
use Modules\FarmLand\Http\Requests\WaterAnalysis\UpdateWaterAnalysisRequest;
use Modules\FarmLand\Models\WaterAnalysis;

class WaterAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWaterAnalysisRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show( WaterAnalysis $waterAnalysis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWaterAnalysisRequest $request, WaterAnalysis $waterAnalysis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaterAnalysis $waterAnalysis)
    {
        //
    }
}
