<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\WaterAnalysis;
use Modules\FarmLand\Services\WaterAnalysis\WaterAnalysisService;
use Modules\FarmLand\Http\Requests\WaterAnalysis\StoreWaterAnalysisRequest;
use Modules\FarmLand\Http\Requests\WaterAnalysis\UpdateWaterAnalysisRequest;

class WaterAnalysisController extends Controller
{
    /**
     * Summary of WaterAnalysisService.
     *
     * @var WaterAnalysisService
     */
    protected WaterAnalysisService $waterAnalysisService;

    /**
     * WaterAnalysisController Constructor.
     *
     * @param WaterAnalysisService $waterAnalysisService
     */
    public function __construct(WaterAnalysisService $waterAnalysisService)
    {
        $this->waterAnalysisService = $waterAnalysisService;
    }

    /**
     * Get All Water Analyses.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', WaterAnalysis::class);
        $data = $this->waterAnalysisService->getAll();

        return ApiResponse::success(
            [$data],
            "SuccessFully Get All Water Analyses.",
            200
        );
    }

    /**
     * Create A New Water Analysis.
     *
     * @param StoreWaterAnalysisRequest $request
     * @return JsonResponse
     */
    public function store(StoreWaterAnalysisRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', WaterAnalysis::class);
            $data = $this->waterAnalysisService->store($request->validated());

            return ApiResponse::success(
                [$data],
                "Analysis Created Successfully.",
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Show The Specified Water Analysis.
     *
     * @param WaterAnalysis $waterAnalysis
     * @return JsonResponse
     */
    public function show(WaterAnalysis $waterAnalysis): JsonResponse
    {
        $this->authorize('view', $waterAnalysis);
        $data = $this->waterAnalysisService->get($waterAnalysis);
        return ApiResponse::success(
            [$data],
            "SuccessFully Get Water Analysis.",
            200
        );
    }

    /**
     * Update The Specified Water Analysis.
     *
     * @param UpdateWaterAnalysisRequest $request
     * @param WaterAnalysis $waterAnalysis
     * @return JsonResponse
     */
    public function update(UpdateWaterAnalysisRequest $request, WaterAnalysis $waterAnalysis): JsonResponse
    {
        try {
            $this->authorize('update', $waterAnalysis);
            $data = $this->waterAnalysisService->update($request->validated(), $waterAnalysis);

            return ApiResponse::success(
                [$data],
                "Analysis Updated Successfully.",
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Delete The Specified Water Analysis.
     *
     * @param WaterAnalysis $waterAnalysis
     * @return JsonResponse
     */
    public function destroy(WaterAnalysis $waterAnalysis): JsonResponse
    {
        try {
            $this->authorize('delete', $waterAnalysis);
            $this->waterAnalysisService->destroy($waterAnalysis);
            return ApiResponse::success(
                [],
                'Analysis Deleted Successfully.',
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }
}
