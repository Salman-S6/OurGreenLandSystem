<?php

use Illuminate\Support\Facades\Route;
use Modules\Extension\Http\Controllers\ExtensionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('extensions', ExtensionController::class)->names('extension');
});
