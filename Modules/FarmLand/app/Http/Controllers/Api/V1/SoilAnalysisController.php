<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Modules\FarmLand\Http\Requests\SoilAnalysis\StoreSoilAnalysisRequest;
use Modules\FarmLand\Http\Requests\SoilAnalysis\UpdateSoilAnalysisRequest;
use Modules\FarmLand\Models\SoilAnalysis;

class SoilAnalysisController extends Controller
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
    public function store(StoreSoilAnalysisRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SoilAnalysis $soilAnalysis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoilAnalysisRequest $request, SoilAnalysis $soilAnalysis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SoilAnalysis $soilAnalysis)
    {
        //
    }
}
