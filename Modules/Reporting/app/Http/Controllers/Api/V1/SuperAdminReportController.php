<?php

namespace Modules\Reporting\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Reporting\Services\SuperAdminReportsService;

class SuperAdminReportController extends Controller
{
    protected SuperAdminReportsService $service;

    public function __construct()
    {
        $this->service = new SuperAdminReportsService();    
    }

    public function productionOverview()
    {
        try {
            Gate::authorize("view-manager-reports");

            $data = $this->service->getProductionOverview();

            return ApiResponse::success([
                "production_overview" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function rehabilitatedAreasSummary()
    {
        try {
            Gate::authorize("view-manager-reports");

            $data = $this->service->getRehabilitatedAreasSummary();

            return ApiResponse::success([
                "rehabilitated_areas_summary" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function finishedProductionEstimationsWithGaps()
    {
        try {
            Gate::authorize("view-manager-reports");

            $data = $this->service->getFinishedProductionEstimationsWithGaps();

            return ApiResponse::success([
                "finished_production_estimations_with_gaps" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

}
