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
    }

    /**
     * Get All Soil Analyses.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', SoilAnalysis::class);
        $data = $this->soilAnalysisService->getAll();

        return ApiResponse::success(
            [$data],
            "SuccessFully Get All Soil Analyses.",
            200
        );
    }

    /**
     * Create A New Soil Analysis.
     *
     * @param StoreSoilAnalysisRequest $request
     * @return JsonResponse
     */
    public function store(StoreSoilAnalysisRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', SoilAnalysis::class);
            $data = $this->soilAnalysisService->store($request->validated());

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
     * Show The Specified Soil Analysis.
     *
     * @param SoilAnalysis $soilAnalysis
     * @return JsonResponse
     */
    public function show(SoilAnalysis $soilAnalysis): JsonResponse
    {
        $this->authorize('view', $soilAnalysis);
        $data = $this->soilAnalysisService->get($soilAnalysis);
        return ApiResponse::success(
            [$data],
            "SuccessFully Get Soil Analysis.",
            200
        );
    }

    /**
     * Update The Specified Soil Analysis.
     *
     * @param UpdateSoilAnalysisRequest $request
     * @param SoilAnalysis $soilAnalysis
     * @return JsonResponse
     */
    public function update(UpdateSoilAnalysisRequest $request, SoilAnalysis $soilAnalysis): JsonResponse
    {
        try {
            $this->authorize('update', $soilAnalysis);
            $data = $this->soilAnalysisService->update($request->validated(), $soilAnalysis);

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
     * Delete The Specified Soil Analysis.
     *
     * @param SoilAnalysis $soilAnalysis
     * @return JsonResponse
     */
    public function destroy(SoilAnalysis $soilAnalysis): JsonResponse
    {
        try {
            $this->authorize('delete', $soilAnalysis);
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
