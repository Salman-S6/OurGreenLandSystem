<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\FarmLandController;
use Modules\FarmLand\Http\Controllers\LandController;
use Modules\FarmLand\Http\Controllers\RehabilitationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
});

Route::apiResource('Reahabilitation', RehabilitationController::class)->names('Reahabilitation');
Route::get('/lands/prioritized', [LandController::class, 'prioritized']);
Route::apiResource('lands', LandController::class)->names('lands');

