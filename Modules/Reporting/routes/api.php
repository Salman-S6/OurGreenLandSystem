<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Http\Controllers\Api\V1\EngineerReportController;
use Modules\Reporting\Http\Controllers\Api\V1\FarmerReportController;
use Modules\Reporting\Http\Controllers\Api\V1\ProductionEstimationReportController;
use Modules\Reporting\Http\Controllers\Api\V1\SuperAdminReportController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('reportings', ReportingController::class)->names('reporting');
// });

Route::group(["prefix" => "reporting/",
    "name" => "reporting.",
    "middleware" => ["auth:sanctum"]
    ], function () {
    /**
     * 
     * -----------------------------------------
     * FarmReportController
     * -----------------------------------------
     */
    Route::get("farmer-lands-summary", [FarmerReportController::class, "farmerLandsSummary"])
        ->name("farmer.lands-summary");

    Route::get("latest-crop-plans", [FarmerReportController::class, "latestCropPlans"])
        ->name("farmer.latest-crop-plans");

    Route::get("recent-guidance", [FarmerReportController::class, "recentGuidance"])
        ->name("farmer.recent-guidance");

    Route::get("crop-plan-status-stats", [FarmerReportController::class, "cropPlanStatusStats"])
        ->name("farmer.crop-plan-status-stats");
    /**
     * 
     * -----------------------------------------
     * EngineerReportController
     * -----------------------------------------
     */
    Route::get("soil-analyses", [EngineerReportController::class, "soilAnalyses"])
        ->name("engineer.soil-analyses");

    Route::get("water-analyses", [EngineerReportController::class, "waterAnalyses"])
        ->name("engineer.water-analyses");

    Route::get("pest-disease-cases", [EngineerReportController::class, "pestDiseaseCases"])
        ->name("engineer.pest-disease-cases");
    /**
     * 
     * -----------------------------------------
     * SuperAdminReportController
     * -----------------------------------------
     */
    Route::get("production-overview", [SuperAdminReportController::class, "productionOverview"])
        ->name("manager.production-overview");

    Route::get("rehabilitated-areas-summary", [SuperAdminReportController::class, "rehabilitatedAreasSummary"])
        ->name("manager.rehabilitated-areas-summary");

    Route::get("finished-production-estimations-with-gaps", [SuperAdminReportController::class, "finishedProductionEstimationsWithGaps"])
        ->name("manager.finished-production-estimations-with-gaps");
    /**
     * 
     * -----------------------------------------
     * ProductionEstimationReportController
     * -----------------------------------------
     */
    Route::get("prod-est-by-crop", [ProductionEstimationReportController::class, "prodEstByCrop"])
        ->name("prod-est.by-crop");

    Route::get("prod-est-by-soil-type", [ProductionEstimationReportController::class, "prodEstBySoilType"])
        ->name("prod-est.by-soil-type");

    Route::get("prod-est-by-region", [ProductionEstimationReportController::class, "prodEstByRegion"])
        ->name("prod-est.by-region");

    Route::get("prod-trends-by-year", [ProductionEstimationReportController::class, "prodTrendsByYear"])
        ->name("prod-trends.by-year");

});