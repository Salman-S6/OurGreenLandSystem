<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\Api\V1\LandController;
use Modules\FarmLand\Http\Controllers\Api\V1\RehabilitationController;
use Modules\FarmLand\Http\Controllers\FarmLandController;



Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
});



Route::prefix('farm-land')->middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('rehabilitation', RehabilitationController::class)->names('farm-land.rehabilitation');

    Route::get('/lands/prioritized', [LandController::class, 'prioritized'])->name('farm-land.prioritized');
    Route::apiResource('lands', LandController::class)->names('farm-land.lands');
});