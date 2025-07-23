<?php

namespace Modules\CropManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\CropGrowthStage\StoreCropGrowthStageRequest;
use Modules\CropManagement\Http\Requests\CropGrowthStage\UpdateCropGrowthStageRequest;
use Modules\CropManagement\Models\CropGrowthStage;

class CropGrowthStageController extends Controller
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
    public function store(StoreCropGrowthStageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CropGrowthStage $cropGrowthStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropGrowthStageRequest $request, CropGrowthStage $cropGrowthStage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropGrowthStage $cropGrowthStage)
    {
        //
    }
}
