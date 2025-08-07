<?php

use Illuminate\Support\Facades\Route;
use Modules\CropManagement\Http\Controllers\Api\V1\BestAgriculturalPracticeController;
use Modules\CropManagement\Http\Controllers\Api\V1\CropController;
use Modules\CropManagement\Http\Controllers\Api\V1\CropGrowthStageController;
use Modules\CropManagement\Http\Controllers\Api\V1\CropPlanController;

use Modules\CropManagement\Http\Controllers\Api\V1\PestDiseaseCaseController;
use Modules\CropManagement\Http\Controllers\Api\V1\PestDiseaseRecommendationController;
use Modules\CropManagement\Http\Controllers\Api\V1\ProductionEstimationController;

Route::prefix('crop-managements')->middleware(['auth:sanctum', "throttle:api"])->group(function () {


    /**
     *
     *
     * ----------------------------------------------------------------
     * CropContoller routes.
     * ----------------------------------------------------------------
     */

    Route::prefix('crops')->group(function () {

        Route::post('/create', [CropController::class, 'store'])->name('crop.create');

        Route::post('/update/{crop}', [CropController::class, 'update'])->name('crop.update');

        Route::get('/get-all', [CropController::class, 'index'])->name('crops.index');

        Route::get('/{crop}', [CropController::class, 'show'])->name('crops.show');

        Route::delete('/{crop}', [CropController::class, 'destroy'])->name('crop.delete');
    });

    /**
     *
     *
     * ----------------------------------------------------------------
     * CropPlanController routes.
     * ----------------------------------------------------------------
     */

    Route::prefix('cropPlan')->group(function () {

        Route::post('/create', [CropPlanController::class, 'store'])->name('cropPlan.make');

        Route::post('/update/{cropPlan}', [CropPlanController::class, 'update'])->name('cropPlan.update');

        Route::get('/{cropPlan}', [CropPlanController::class, 'show'])->name('cropPlan.show');

        Route::get('/all/paln', [CropPlanController::class, 'index'])->name('cropPaln.all');

        Route::post('/change-to-cancelled/{cropPlan}', [CropPlanController::class, 'switchStatusToCancelled'])->name('cropPlan.cancelled');

        Route::delete('/{cropPlan}', [CropPlanController::class, 'destroy'])->name('cropPlan.destroy');
    });

    /**
     *
     *
     * ----------------------------------------------------------------
     * ProductionEstimationController routes.
     * ----------------------------------------------------------------
     */

    Route::prefix('production-estimation')->group(function () {

        Route::post('/create', [ProductionEstimationController::class, 'store'])->name('estimation.create');

        Route::post('/update/{productionEstimation}', [ProductionEstimationController::class, 'update'])->name('estimation.update');

        Route::get('/get/{productionEstimation}', [ProductionEstimationController::class, 'show'])->name('estimation.show');

        Route::get('/all', [ProductionEstimationController::class, 'index'])->name('estimation.all');

        Route::delete('/{productionEstimation}', [ProductionEstimationController::class, 'destroy'])->name('estimation.delete');
    });

    /**
     *
     *
     * ----------------------------------------------------------------
     * PestDiseaseCaseController routes.
     * ----------------------------------------------------------------
     */

    Route::prefix('disease-case')->group(function () {

        Route::post('/create', [PestDiseaseCaseController::class, 'store'])->name('case.make');

        Route::post('/update/{pestDiseaseCase}', [PestDiseaseCaseController::class, 'update'])->name('case.update');

        Route::get('/get-all', [PestDiseaseCaseController::class, 'index'])->name('case.all');

        Route::get('/get/{pestDiseaseCase}', [PestDiseaseCaseController::class, 'show'])->name('case.get');

        Route::delete('/{pestDiseaseCase}', [PestDiseaseCaseController::class, 'destroy'])->name('case.delete');
    });

    /**
     *
     *
     * ----------------------------------------------------------------
     * CropGrowthStageController routes.
     * ----------------------------------------------------------------
     */

    Route::resource('cropGrowthStage', CropGrowthStageController::class)->names('crop_management.cropGrowthStage');
    Route::delete('{cropGrowthStage}', [CropGrowthStageController::class, 'forceDestroy']);

    /**
     *
     *
     * ----------------------------------------------------------------
     * BestAgriculturalPracticeController routes.
     * ----------------------------------------------------------------
     */
    
    Route::resource('bestAgriculturalPractice', BestAgriculturalPracticeController::class)->names('crop_management.bestAgriculturalPractice');
    Route::delete('{bestAgriculturalPractice}', [BestAgriculturalPracticeController::class, 'forceDestroy']);


    /**
     *
     *
     * ----------------------------------------------------------------
     * PestDiseaseRecommendationController routes.
     * ----------------------------------------------------------------
     */


    Route::resource('pestDiseaseRecommendation', PestDiseaseRecommendationController::class)->names('crop_management.pestDiseaseRecommendation');
    Route::delete('{pestDiseaseRecommendation}', [PestDiseaseRecommendationController::class, 'forceDestroy']);
});
