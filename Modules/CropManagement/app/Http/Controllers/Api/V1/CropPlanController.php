<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\CropManagement\Http\Requests\CropPlan\StoreCropPlanRequest;
use Modules\CropManagement\Http\Requests\CropPlan\UpdateCropPlanRequest;
use Modules\CropManagement\Interfaces\Crops\CropPlanInterface;
use Modules\CropManagement\Models\CropPlan;

class CropPlanController extends Controller
{
    protected  $cropPlan;

    public function __construct(CropPlanInterface $cropPlan)
    {
        $this->cropPlan = $cropPlan;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = $this->cropPlan->getAll();

        return ApiResponse::success(
            $plans,
            'Successfully retrieved crop plans',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropPlanRequest $request)
    {
        $plan = $this->cropPlan->store($request->validated());

        return ApiResponse::success(
            [$plan],
            'Successfully created crop plan',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(CropPlan $cropPlan)
    {
        $plan = $this->cropPlan->show($cropPlan);

        return ApiResponse::success(
            $plan,
            'Successfully retrieved crop plan',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropPlanRequest $request, CropPlan $cropPlan)
    {
        $plan = $this->cropPlan->update($request->validated(), $cropPlan);

        return ApiResponse::success(
            [$plan],
            'Successfully updated crop plan',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropPlan $cropPlan)
    {
        $deleted = $this->cropPlan->destroy($cropPlan);

        return ApiResponse::success(
            ['deleted' => $deleted],
            'Successfully deleted crop plan',
            200
        );
    }

    /**
     * Switch status to cancelled.
     */
    public function switchStatusToCancelled(CropPlan $cropPlan)
    {
        $plan = $this->cropPlan->switchStatusToCancelled($cropPlan);

        return ApiResponse::success(
            $plan['plan'],
            'Successfully cancelled crop plan',
            200
        );
    }
}
