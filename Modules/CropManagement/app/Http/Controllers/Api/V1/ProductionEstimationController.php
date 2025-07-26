<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\ProductionEstimation\StoreProductionEstimationRequest;
use Modules\CropManagement\Http\Requests\ProductionEstimation\UpdateProductionEstimationRequest;
use Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface;
use Modules\CropManagement\Models\ProductionEstimation;

class ProductionEstimationController extends Controller
{
    protected  $productionEstimation;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface $productionEstimation
     */
    public  function  __construct(ProductionEstimationInterface $productionEstimation){

        $this->productionEstimation=$productionEstimation;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=$this->productionEstimation->index();
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionEstimationRequest $request)
    {
        $data=$this->productionEstimation->store($request);
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionEstimation $productionEstimation)
    {
        $data=$this->productionEstimation->getProductionEstimation($productionEstimation);
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionEstimationRequest $request, ProductionEstimation $productionEstimation)
    {
        $data=$this->productionEstimation->update($productionEstimation,$request);
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionEstimation $productionEstimation)
    {
        $data=$this->productionEstimation->destroy($productionEstimation);
        return ApiResponse::success(
            ['success'=>true],
            $data['message'],
            200
        );
    }
}
