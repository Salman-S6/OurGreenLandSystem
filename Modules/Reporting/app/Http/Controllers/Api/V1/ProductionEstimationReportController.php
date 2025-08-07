<?php

namespace Modules\Reporting\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Reporting\Services\ProductionEstimationReportsService;

class ProductionEstimationReportController extends Controller
{
    public function prodEstByCrop()
    {
        try {
            Gate::authorize('view-production-estimation-reports');
    
            $data = ProductionEstimationReportsService::getProdEstByCrop();
            
            return ApiResponse::success([
                "report" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function prodEstBySoilType()
    {
        try {
            Gate::authorize('view-production-estimation-reports');
    
            $data = ProductionEstimationReportsService::getProdEstBySoilType();
            
            return ApiResponse::success([
                "report" => $data
            ]);
            
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(errors: [
                "error" => $e->getMessage()
            ]);
        }
    }

    public function prodEstByRegion()
    {
        try {
            Gate::authorize('view-production-estimation-reports');
    
            $data = ProductionEstimationReportsService::getProdEstByRegion();
            
            return ApiResponse::success([
                "report" => $data
            ]);
            
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function prodTrendsByYear()
    {
        try {
            Gate::authorize('view-production-estimation-reports');
    
            $data = ProductionEstimationReportsService::getProdTrendsByYear();
            
            return ApiResponse::success([
                "report" => $data
            ]);
            
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
