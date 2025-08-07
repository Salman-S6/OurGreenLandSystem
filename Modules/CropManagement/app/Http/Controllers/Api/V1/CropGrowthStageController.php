<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\CropManagement\Http\Requests\CropGrowthStage\StoreCropGrowthStageRequest;
use Modules\CropManagement\Http\Requests\CropGrowthStage\UpdateCropGrowthStageRequest;
use Modules\CropManagement\Models\CropGrowthStage;
use Modules\CropManagement\Services\Crops\CropGrowthStageService;
use Modules\CropManagement\Http\Resources\CropGrowthStageResource;

class CropGrowthStageController extends Controller
{
    public function __construct(protected CropGrowthStageService $service)
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(CropGrowthStage::class, 'cropGrowthStage');
        $this->middleware('can:forceDelete,cropGrowthStage')->only('forceDestroy');
    }

    public function index()
    {
        $filters = [
            'crop_plan_id' => request()->query('crop_plan_id'),
            'recorded_by' => auth()->id(),
        ];

        $cropGrowthStages = $this->service->getAll($filters);
        return ApiResponse::success([
            'stages' => CropGrowthStageResource::collection($cropGrowthStages),
        ], 'Crop growth stages retrieved successfully', 200);
    }

    public function store(StoreCropGrowthStageRequest $request)
    {
        $data = $request->validated();
        $data['recorded_by'] = auth()->id();

        $cropGrowthStage = $this->service->store($data);
        return ApiResponse::success(
            ['stage' => new CropGrowthStageResource($cropGrowthStage->load(['cropPlan', 'recorder']))],
            'Crop growth stage created successfully',
            201
        );
    }

    public function show(CropGrowthStage $cropGrowthStage)
    {
        return ApiResponse::success(
            ['stage' => new CropGrowthStageResource($this->service->get($cropGrowthStage)->load(['cropPlan', 'recorder']))],
            'Crop growth stage retrieved successfully'
        );
    }

    public function update(UpdateCropGrowthStageRequest $request, CropGrowthStage $cropGrowthStage)
    {
        $data = $request->validated();
        $data['recorded_by'] = auth()->id();
        $cropGrowthStage = $this->service->update($data, $cropGrowthStage);
        return ApiResponse::success(
            ['stage' =>  new CropGrowthStageResource($cropGrowthStage->load(['cropPlan', 'recorder']))],
            'Crop growth stage updated successfully'
        );
    }

    public function destroy(CropGrowthStage $cropGrowthStage)
    {
        $this->service->destroy($cropGrowthStage);
        return ApiResponse::success([], 'Crop growth stage deleted successfully');
    }

    public function forceDestroy(CropGrowthStage $cropGrowthStage)
    {
        $this->service->forceDestroy($cropGrowthStage);
        return ApiResponse::success([], 'Crop growth stage permanently deleted successfully');
    }
}
