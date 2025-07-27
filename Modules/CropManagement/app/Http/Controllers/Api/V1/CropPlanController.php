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
    protected $cropPlan;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Interfaces\Crops\CropPlanInterface $cropPlan
     */
    public function __construct(CropPlanInterface $cropPlan)
    {
        $this->cropPlan = $cropPlan;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data=$this->cropPlan->index();
      ApiResponse::success(
        [$data['plans']],
        $data['message'],
        200
      );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropPlanRequest $request)

    {
        $data = $this->cropPlan->store($request);
        return ApiResponse::success(
            [$data['plan']],
            $data['message'],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(CropPlan $cropPlan)
    {
        $data=$this->cropPlan->show($cropPlan);
         ApiResponse::success([$data['plan'],$data['message'],200]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropPlanRequest $request, CropPlan $cropPlan)
    {
        $data=$this->cropPlan->update($request,$cropPlan);
        ApiResponse::success([$data['plan']],$data['message'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropPlan $cropPlan)
    {
        $data=$this->cropPlan->destroy($cropPlan);
        ApiResponse::success(['status'=>true],$data['message'],200);
    }

    /**
     * Summary of switchStatusToCancelled
     * @param \Modules\CropManagement\Models\CropPlan $cropPlan
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchStatusToCancelled(CropPlan $cropPlan){
        $data=$this->cropPlan->switchStatusToCancelled($cropPlan);
        return ApiResponse::success([$data['plan']],$data['message'],200);
    }
}
