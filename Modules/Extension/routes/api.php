<?php

use Illuminate\Support\Facades\Route;
use Modules\Extension\Http\Controllers\Api\V1\AgriculturalAlertController;
use Modules\Extension\Http\Controllers\Api\V1\AgriculturalGuidanceController;
use Modules\Extension\Http\Controllers\Api\V1\AnswerController;
use Modules\Extension\Http\Controllers\Api\V1\QuestionController;
use Modules\Extension\Http\Controllers\ExtensionController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('extensions', ExtensionController::class)->names('extension');
// });

Route::middleware(["auth:sanctum", "throttle:api"])
    ->name("extension.")->group(function () {

    /**
     *  
     * ----------------------------------------------------------------
     * AgriculturalGuidanceController routes.
     * -----------------------------------------------------------------
    */
    Route::apiResource('agricultural-guidances',
        AgriculturalGuidanceController::class);

    /**
     *  
     * ----------------------------------------------------------------
     * AgriculturalAlertController routes.
     * -----------------------------------------------------------------
    */
    Route::apiResource('crop-plans.agricultural-alerts',
        AgriculturalAlertController::class);

    /**
     *  
     * ----------------------------------------------------------------
     * QuestionController routes.
     * -----------------------------------------------------------------
    */
    Route::apiResource('questions', QuestionController::class);

    /**
     *  
     * ----------------------------------------------------------------
     * AnswerController routes.
     * -----------------------------------------------------------------
    */
    Route::apiResource('questions.answers', AnswerController::class)
        ->except(['index', 'show']);



});
