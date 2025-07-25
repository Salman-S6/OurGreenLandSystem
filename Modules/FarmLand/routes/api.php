<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\FarmLandController;
use Modules\FarmLand\Http\Controllers\Api\V1\SoilAnalysisController;
use Modules\FarmLand\Http\Controllers\Api\V1\WaterAnalysisController;
use Modules\FarmLand\Http\Controllers\Api\V1\IdealAnalysisValueController;
use Modules\FarmLand\Http\Controllers\Api\V1\AgriculturalInfrastructureController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
});













Route::apiResource('soil-analyses', SoilAnalysisController::class);

Route::apiResource('water-analyses', WaterAnalysisController::class);

Route::apiResource('agricultural-infrastructures', AgriculturalInfrastructureController::class);

Route::apiResource('ideal-analysis-values', IdealAnalysisValueController::class);
