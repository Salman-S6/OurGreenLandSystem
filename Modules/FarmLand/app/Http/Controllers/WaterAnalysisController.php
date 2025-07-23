<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\WaterAnalysis;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\FarmLand\Services\WaterAnalysis\WaterAnalysisService;
use Modules\FarmLand\Http\Requests\WaterAnalysis\StoreWaterAnalysisRequest;
use Modules\FarmLand\Http\Requests\WaterAnalysis\UpdateWaterAnalysisRequest;

class WaterAnalysisController extends Controller
{
    use AuthorizesRequests;

    protected WaterAnalysisService $waterAnalysisService;

    /**
     * Summary of __construct
     * @param WaterAnalysisService $waterAnalysisService
     */
    public function __construct(WaterAnalysisService $waterAnalysisService)
    {
        $this->waterAnalysisService = $waterAnalysisService;
        // $this->authorizeResource(WaterAnalysis::class, 'water_analysis');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->waterAnalysisService->getAll();

        return ApiResponse::success(
            $data,
            "SuccessFully Get All Water Analyses",
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWaterAnalysisRequest $request)
    {
        try {
            $data = $this->waterAnalysisService->store($request);

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
    public function show(WaterAnalysis $waterAnalysis)
    {
        $data = $this->waterAnalysisService->getWaterAnalysis($waterAnalysis);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Water Analysis",
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWaterAnalysisRequest $request, WaterAnalysis $waterAnalysis)
    {
        try {
            $data = $this->waterAnalysisService->update($request, $waterAnalysis);

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
    public function destroy(WaterAnalysis $waterAnalysis)
    {
        try {
            $this->waterAnalysisService->destroy($waterAnalysis);
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
