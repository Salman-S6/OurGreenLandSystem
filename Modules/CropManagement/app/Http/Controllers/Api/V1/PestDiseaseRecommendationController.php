<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\CropManagement\Http\Requests\PestDiseaseRecommendation\StorePestDiseaseRecommendationRequest;
use Modules\CropManagement\Http\Requests\PestDiseaseRecommendation\UpdatePestDiseaseRecommendationRequest;
use Modules\CropManagement\Http\Resources\PestDiseaseRecommendationResource;
use Modules\CropManagement\Models\PestDiseaseRecommendation;
use Modules\CropManagement\Services\Crops\PestDiseaseRecommendationService;

class PestDiseaseRecommendationController extends Controller
{
    public function __construct(protected PestDiseaseRecommendationService $service)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('can:forceDelete,bestAgriculturalPractice')->only('forceDestroy');
        $this->authorizeResource(PestDiseaseRecommendation::class, 'pestDiseaseRecommendation');
    }

    public function index()
    {
        $filters = [
            'growth_stage_id' => request()->query('growth_stage_id'),
            'expert_id' => auth()->id(),
        ];

        $bestAgriculturalPractices = $this->service->getAll($filters);
        return ApiResponse::success([
            'practices' => PestDiseaseRecommendationResource::collection($bestAgriculturalPractices),
        ], 'Best Agricultural Practices retrieved successfully', 200);
    }

    public function store(StorePestDiseaseRecommendationRequest $request)
    {
        $data = $request->validated();
        $data['expert_id'] = auth()->id();
        // dd($data);
        $bestAgriculturalPractice = $this->service->store($data);
        return ApiResponse::success(
            ['practice' => new PestDiseaseRecommendationResource($bestAgriculturalPractice->load(['growthStage', 'expert']))],
            'Pest Disease Recommendation created successfully',
            201
        );
    }

    public function show(PestDiseaseRecommendation $bestAgriculturalPractice)
    {
        return ApiResponse::success(
            ['practice' => new PestDiseaseRecommendationResource($this->service->get($bestAgriculturalPractice)->load(['growthStage', 'expert']))],
            'Pest Disease Recommendation retrieved successfully'
        );
    }

    public function update(UpdatePestDiseaseRecommendationRequest $request, PestDiseaseRecommendation $bestAgriculturalPractice)
    {
        $data = $request->validated();
        $data['recorded_by'] = auth()->id();
        $bestAgriculturalPractice = $this->service->update($data, $bestAgriculturalPractice);
        return ApiResponse::success(
            ['practice' =>  new PestDiseaseRecommendationResource($bestAgriculturalPractice->load(['growthStage', 'expert']))],
            'Pest Disease Recommendation updated successfully'
        );
    }

    public function destroy(PestDiseaseRecommendation $bestAgriculturalPractice)
    {
        $this->service->destroy($bestAgriculturalPractice);
        return ApiResponse::success([], 'Pest Disease Recommendation deleted successfully');
    }
    public function forceDestroy(PestDiseaseRecommendation $bestAgriculturalPractice)
    {
        $this->service->forceDestroy($bestAgriculturalPractice);
        return ApiResponse::success([], 'Pest Disease Recommendation permanently deleted successfully');
    }
}
