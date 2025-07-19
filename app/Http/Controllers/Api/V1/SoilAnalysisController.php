<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoilAnalysis\StoreSoilAnalysisRequest;
use App\Http\Requests\SoilAnalysis\UpdateSoilAnalysisRequest;
use App\Models\SoilAnalysis;

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
