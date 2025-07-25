<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\PestDiseaseRecommendation\StorePestDiseaseRecommendationRequest;
use Modules\CropManagement\Http\Requests\PestDiseaseRecommendation\UpdatePestDiseaseRecommendationRequest;
use Modules\CropManagement\Models\PestDiseaseRecommendation;

class PestDiseaseRecommendationController extends Controller
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
    public function store(StorePestDiseaseRecommendationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PestDiseaseRecommendation $pestDiseaseRecommendation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePestDiseaseRecommendationRequest $request, PestDiseaseRecommendation $pestDiseaseRecommendation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PestDiseaseRecommendation $pestDiseaseRecommendation)
    {
        //
    }
}
