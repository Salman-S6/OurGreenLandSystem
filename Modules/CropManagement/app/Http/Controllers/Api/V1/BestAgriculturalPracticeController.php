<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Modules\CropManagement\Http\Requests\BestAgriculturalPractice\StoreBestAgriculturalPracticeRequest;
use Modules\CropManagement\Http\Requests\BestAgriculturalPractice\UpdateBestAgriculturalPracticeRequest;
use Modules\CropManagement\Models\BestAgriculturalPractice;
use Modules\CropManagement\Services\Crops\BestAgriculturalPracticeService;

use Modules\CropManagement\Http\Resources\BestAgriculturalPracticeResource;

class BestAgriculturalPracticeController extends Controller
{
    public function __construct(protected BestAgriculturalPracticeService $service)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('can:forceDelete,bestAgriculturalPractice')->only('forceDestroy');
        $this->authorizeResource(BestAgriculturalPractice::class, 'bestAgriculturalPractice');
    }

    public function index()
    {
        $filters = [
            'growth_stage_id' => request()->query('growth_stage_id'),
            'expert_id' => auth()->id(),
        ];

        $bestAgriculturalPractices = $this->service->getAll($filters);
        return ApiResponse::success([
            'practices' => BestAgriculturalPracticeResource::collection($bestAgriculturalPractices),
        ], 'Best Agricultural Practices retrieved successfully', 200);
    }

    public function store(StoreBestAgriculturalPracticeRequest $request)
    {
        $data = $request->validated();
        $data['expert_id'] = auth()->id();
        // dd($data);
        $bestAgriculturalPractice = $this->service->store($data);
        return ApiResponse::success(
            ['practice' => new BestAgriculturalPracticeResource($bestAgriculturalPractice->load(['growthStage', 'expert']))],
            'Best Agricultural Practice created successfully',
            201
        );
    }

    public function show(BestAgriculturalPractice $bestAgriculturalPractice)
    {
        return ApiResponse::success(
            ['practice' => new BestAgriculturalPracticeResource($this->service->get($bestAgriculturalPractice)->load(['growthStage', 'expert']))],
            'Best Agricultural Practice retrieved successfully'
        );
    }

    public function update(UpdateBestAgriculturalPracticeRequest $request, BestAgriculturalPractice $bestAgriculturalPractice)
    {
        $data = $request->validated();
        $data['recorded_by'] = auth()->id();
        $bestAgriculturalPractice = $this->service->update($data, $bestAgriculturalPractice);
        return ApiResponse::success(
            ['practice' =>  new BestAgriculturalPracticeResource($bestAgriculturalPractice->load(['growthStage', 'expert']))],
            'Best Agricultural Practice updated successfully'
        );
    }

    public function destroy(BestAgriculturalPractice $bestAgriculturalPractice)
    {
        $this->service->destroy($bestAgriculturalPractice);
        return ApiResponse::success([], 'Best Agricultural Practice deleted successfully');
    }
    public function forceDestroy(BestAgriculturalPractice $bestAgriculturalPractice)
    {
        $this->service->forceDestroy($bestAgriculturalPractice);
        return ApiResponse::success([], 'Best Agricultural Practice permanently deleted successfully');
    }
}
