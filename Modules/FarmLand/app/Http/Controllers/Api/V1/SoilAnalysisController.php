<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\SoilAnalysis;
use Modules\FarmLand\Services\SoilAnalysis\SoilAnalysisService;
use Modules\FarmLand\Http\Requests\SoilAnalysis\StoreSoilAnalysisRequest;
use Modules\FarmLand\Http\Requests\SoilAnalysis\UpdateSoilAnalysisRequest;

class SoilAnalysisController extends Controller
{
    /**
     * Summary of SoilAnalysisService.
     *
     * @var SoilAnalysisService
     */
    protected SoilAnalysisService $soilAnalysisService;

    /**
     * SoilAnalysisController Constructor.
     *
     * @param SoilAnalysisService $soilAnalysisService
     */
    public function __construct(SoilAnalysisService $soilAnalysisService)
    {
        $this->soilAnalysisService = $soilAnalysisService;
        // $this->authorizeResource(SoilAnalysis::class, 'water_analysis');
    }

    /**
     * Get All Soil Analyses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->soilAnalysisService->getAll();

        return ApiResponse::success(
            $data,
            "SuccessFully Get All Soil Analyses.",
            200
        );
    }

    /**
     * Create A New Soil Analysis.
     *
     * @param \Modules\FarmLand\Http\Requests\SoilAnalysis\StoreSoilAnalysisRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSoilAnalysisRequest $request): JsonResponse
    {
        try {
            $data = $this->soilAnalysisService->store($request);

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
     * Show The Specified Soil Analysis.
     *
     * @param \Modules\FarmLand\Models\SoilAnalysis $soilAnalysis
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SoilAnalysis $soilAnalysis): JsonResponse
    {
        $data = $this->soilAnalysisService->getSoilAnalysis($soilAnalysis);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Soil Analysis.",
            200
        );
    }

    /**
     * Update The Specified Soil Analysis.
     *
     * @param \Modules\FarmLand\Http\Requests\SoilAnalysis\UpdateSoilAnalysisRequest $request
     * @param \Modules\FarmLand\Models\SoilAnalysis $soilAnalysis
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSoilAnalysisRequest $request, SoilAnalysis $soilAnalysis): JsonResponse
    {
        try {
            $data = $this->soilAnalysisService->update($request, $soilAnalysis);

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
     * Delete The Specified Soil Analysis.
     *
     * @param \Modules\FarmLand\Models\SoilAnalysis $soilAnalysis
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SoilAnalysis $soilAnalysis): JsonResponse
    {
        try {
            $this->soilAnalysisService->destroy($soilAnalysis);
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
