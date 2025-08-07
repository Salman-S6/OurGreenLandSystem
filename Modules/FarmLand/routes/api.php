<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\Api\V1\LandController;
use Modules\FarmLand\Http\Controllers\Api\V1\RehabilitationController;
use Modules\FarmLand\Http\Controllers\FarmLandController;
use Modules\FarmLand\Http\Controllers\Api\V1\SoilAnalysisController;
use Modules\FarmLand\Http\Controllers\Api\V1\WaterAnalysisController;
use Modules\FarmLand\Http\Controllers\Api\V1\IdealAnalysisValueController;
use Modules\FarmLand\Http\Controllers\Api\V1\AgriculturalInfrastructureController;



Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
});


Route::prefix('farm-land')->middleware(['auth:sanctum','throttle:farm-land-api'])->group(function () {

    Route::apiResource('rehabilitation', RehabilitationController::class)->names('farm-land.rehabilitation');

    Route::get('/lands/prioritized', [LandController::class, 'prioritized'])->name('farm-land.prioritized');
    Route::apiResource('lands', LandController::class)->names('farm-land.lands');

    Route::apiResource('soil-analyses', SoilAnalysisController::class)->names('farm_land.soil_analyses');

    Route::apiResource('water-analyses', WaterAnalysisController::class)->names('farm_land.water_analyses');

    Route::apiResource('ideal-analysis-values', IdealAnalysisValueController::class)->names('farm_land.ideal_analysis_values');

    Route::apiResource('agricultural-infrastructures', AgriculturalInfrastructureController::class)->names('farm_land.agricultural_infrastructures');
});
