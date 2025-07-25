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
        // $this->authorizeResource(WaterAnalysis::class, 'water_analysis');
    }

    /**
     * Get All Water Analyses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->waterAnalysisService->getAll();

        return ApiResponse::success(
            $data,
            "SuccessFully Get All Water Analyses.",
            200
        );
    }

    /**
     * Create A New Water Analysis.
     *
     * @param \Modules\FarmLand\Http\Requests\WaterAnalysis\StoreWaterAnalysisRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreWaterAnalysisRequest $request): JsonResponse
    {
        try {
            $data = $this->waterAnalysisService->store($request);

            return ApiResponse::success(
                $data,
                "Analysis Created Successfully.",
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Show The Specified Water Analysis.
     */
    public function show(WaterAnalysis $waterAnalysis): JsonResponse
    {
        $data = $this->waterAnalysisService->getWaterAnalysis($waterAnalysis);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Water Analysis.",
            200
        );
    }

    /**
     * Update The Specified Water Analysis.
     */
    public function update(UpdateWaterAnalysisRequest $request, WaterAnalysis $waterAnalysis): JsonResponse
    {
        try {
            $data = $this->waterAnalysisService->update($request, $waterAnalysis);

            return ApiResponse::success(
                $data,
                "Analysis Updated Successfully.",
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Delete The Specified Water Analysis.
     */
    public function destroy(WaterAnalysis $waterAnalysis): JsonResponse
    {
        try {
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
