<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\FarmLandController;
use Modules\FarmLand\Http\Controllers\SoilAnalysisController;
use Modules\FarmLand\Http\Controllers\WaterAnalysisController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
});

Route::apiResource('soil-analyses', SoilAnalysisController::class);

Route::apiResource('water-analyses', WaterAnalysisController::class);
