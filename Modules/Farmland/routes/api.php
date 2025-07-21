<?php

use Illuminate\Support\Facades\Route;
use Modules\Farmland\Http\Controllers\FarmlandController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', FarmlandController::class)->names('farmland');
});
