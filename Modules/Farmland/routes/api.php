<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmLand\Http\Controllers\FarmLandController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
<<<<<<< HEAD
    Route::apiResource('farmlands', Controller::class)->names('farmland');

=======
    Route::apiResource('farmlands', FarmLandController::class)->names('farmland');
>>>>>>> origin/farm-land-management
});
