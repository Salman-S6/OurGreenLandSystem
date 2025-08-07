<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\ProductionEstimation\StoreProductionEstimationRequest;
use Modules\CropManagement\Http\Requests\ProductionEstimation\UpdateProductionEstimationRequest;
use Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface;
use Modules\CropManagement\Models\ProductionEstimation;

class ProductionEstimationController extends Controller
{
    protected  ProductionEstimationInterface $productionEstimation;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface $productionEstimation
     */
    public  function  __construct(ProductionEstimationInterface $productionEstimation)
    {

        $this->productionEstimation = $productionEstimation;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productionEstimation->getAll();
        return ApiResponse::success(
            [$data],
            'Successfully retrieved production estimations',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionEstimationRequest $request)
    {
        $data = $this->productionEstimation->store($request->validated());
        return ApiResponse::success(
            [$data],
            'Successfully created new production estimation.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionEstimation $productionEstimation)
    {
        $data = $this->productionEstimation->get($productionEstimation);
        return ApiResponse::success(
            [$data],
            'Successfully retrieved production estimation.',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionEstimationRequest $request, ProductionEstimation $productionEstimation)
    {
        $updatedEstimation = $this->productionEstimation->update($request->validated(), $productionEstimation);
        return ApiResponse::success(
            [$updatedEstimation],
            'Successfully updated production estimation.',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionEstimation $productionEstimation)
    {
        $this->productionEstimation->destroy($productionEstimation);

        return ApiResponse::success(
            ['success' => true],
            'Successfully deleted production estimation.',
            200
        );
    }
}
