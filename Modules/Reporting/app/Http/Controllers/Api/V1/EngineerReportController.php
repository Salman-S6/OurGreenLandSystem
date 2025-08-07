<?php

namespace Modules\Reporting\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Reporting\Services\EngineerReportsService;

class EngineerReportController extends Controller
{
    protected EngineerReportsService $service;

    public function __construct()
    {
        $this->service = new EngineerReportsService();
    }

    public function soilAnalyses()
    {
        try {
            Gate::authorize("view-engineer-reports");

            $data = $this->service->getSoilAnalyses();

            return ApiResponse::success([
                "soil_analysis" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function waterAnalyses()
    {
        try {
            Gate::authorize("view-engineer-reports");

            $data = $this->service->getWaterAnalyses();

            return ApiResponse::success([
                "water_analysis" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    public function pestDiseaseCases()
    {
        try {
            Gate::authorize("view-engineer-reports");

            $data = $this->service->getPestDiseaseCases();

            return ApiResponse::success([
                "pest_disease_cases" => $data
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
