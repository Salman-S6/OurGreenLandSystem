<?php

use Illuminate\Support\Facades\Route;
use Modules\CropManagement\Http\Controllers\CropManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cropmanagements', CropManagementController::class)->names('cropmanagement');
});
