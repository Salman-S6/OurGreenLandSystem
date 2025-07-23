<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\SoilAnalysis;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\FarmLand\Services\SoilAnalyses\SoilAnalysisService;
use Modules\FarmLand\Http\Requests\SoilAnalysis\StoreSoilAnalysisRequest;
use Modules\FarmLand\Http\Requests\SoilAnalysis\UpdateSoilAnalysisRequest;

class SoilAnalysisController extends Controller
{
    use AuthorizesRequests;

    protected SoilAnalysisService $soilAnalysisService;

    /**
     * Summary of __construct
     * @param SoilAnalysisService $soilAnalysisService
     */
    public function __construct(SoilAnalysisService $soilAnalysisService)
    {
        $this->soilAnalysisService = $soilAnalysisService;
        // $this->authorizeResource(SoilAnalysis::class, 'water_analysis');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->soilAnalysisService->getAll();

        return ApiResponse::success(
            $data,
            "SuccessFully Get All Soil Analyses",
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSoilAnalysisRequest $request)
    {
        try {
            $data = $this->soilAnalysisService->store($request);

            return ApiResponse::success(
                $data,
                "Analysis Created Successfully",
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SoilAnalysis $soilAnalysis)
    {
        $data = $this->soilAnalysisService->getSoilAnalysis($soilAnalysis);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Soil Analysis",
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoilAnalysisRequest $request, SoilAnalysis $soilAnalysis)
    {
        try {
            $data = $this->soilAnalysisService->update($request, $soilAnalysis);

            return ApiResponse::success(
                $data,
                "Analysis Updated Successfully",
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SoilAnalysis $soilAnalysis)
    {
        try {
            $this->soilAnalysisService->destroy($soilAnalysis);
            return ApiResponse::success(
                [],
                'Analysis Deleted',
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }
}
