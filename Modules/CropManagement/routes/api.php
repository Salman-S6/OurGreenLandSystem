<?php

use Illuminate\Support\Facades\Route;
use Modules\CropManagement\Http\Controllers\Api\V1\CropController;
use Modules\CropManagement\Http\Controllers\Api\V1\CropPlanController;
use Modules\CropManagement\Http\Controllers\Api\V1\ProductionEstimationController;

Route::middleware(['auth:sanctum'])->group(function () {


    Route::prefix('crops')->group(function () {
        Route::post('/create', [CropController::class, 'store'])->name('crop.create');
        Route::post('/update/{crop}', [CropController::class, 'update'])->name('crop.update');
        Route::get('/get-all', [CropController::class, 'index'])->name('crops.index');
        Route::get('/{crop}', [CropController::class, 'show'])->name('crops.show');
        Route::delete('/{crop}', [CropController::class, 'destroy'])->name('crop.delete');
    });



    Route::prefix('cropPlan')->group(function () {
        Route::post('/create', [CropPlanController::class, 'store'])->name('cropPlan.make');
        Route::post('/update/{cropPlan}', [CropPlanController::class, 'update'])->name('cropPlan.update');
        Route::get('/{cropPlan}', [CropPlanController::class, 'show'])->name('cropPlan.show');
        Route::get('/all/paln', [CropPlanController::class, 'index'])->name('cropPaln.all');
        Route::post('/change-to-cancelled/{cropPlan}', [CropPlanController::class, 'switchStatusToCancelled'])->name('cropPlan.cancelled');
        Route::delete('/{cropPlan}', [CropPlanController::class, 'destroy'])->name('cropPlan.destroy');
    });


    Route::prefix('production-estimation')->group(function () {
        Route::post('/create', [ProductionEstimationController::class, 'store'])->name('estimation.create');
        Route::post('/update/{productionEstimation}', [ProductionEstimationController::class, 'update'])->name('estimation.update');
        Route::get('/get/{productionEstimation}', [ProductionEstimationController::class, 'show'])->name('estimation.show');
        Route::get('/all', [ProductionEstimationController::class, 'index'])->name('estimation.all');
        Route::delete('/{productionEstimation}', [ProductionEstimationController::class, 'destroy'])->name('estimation.delete');
    });
});
