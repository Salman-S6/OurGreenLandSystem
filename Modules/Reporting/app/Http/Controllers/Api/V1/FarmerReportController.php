<?php

namespace Modules\Reporting\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Reporting\Services\FarmerReportsService;

class FarmerReportController extends Controller
{
    protected FarmerReportsService $service;

    public function __construct()
    {
        $this->service = new FarmerReportsService();
    }

    public function farmerLandsSummary()
    {
        try {
            Gate::authorize("view-farmer-reports");

            $data = $this->service->getFarmerLandsSummary();

            return ApiResponse::success($data);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }

    }
    
    public function latestCropPlans()
    {
        try {
            Gate::authorize("view-farmer-reports");

            $data = $this->service->getLatestCropPlans();
            
            return ApiResponse::success([
                "latest_plans" => $data 
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function recentGuidance()
    {
        try {
            Gate::authorize("view-farmer-reports");

            $data = $this->service->getRecentGuidance();

            return ApiResponse::success([
                "recent_guidance" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function cropPlanStatusStats()
    {
        try {
            Gate::authorize("view-farmer-reports");
            
            $data = $this->service->getCropPlanStatusStats();
            
            return ApiResponse::success($data);
        
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
