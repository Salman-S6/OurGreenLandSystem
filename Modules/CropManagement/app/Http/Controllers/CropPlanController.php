<?php

namespace Modules\CropManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\CropPlan\StoreCropPlanRequest;
use Modules\CropManagement\Http\Requests\CropPlan\UpdateCropPlanRequest;
use Modules\CropManagement\Models\CropPlan;

class CropPlanController extends Controller
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
    public function store(StoreCropPlanRequest $request)

    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CropPlan $cropPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropPlanRequest $request, CropPlan $cropPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropPlan $cropPlan)
    {
        //
    }
}
