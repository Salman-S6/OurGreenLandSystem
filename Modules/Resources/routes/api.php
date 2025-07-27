<?php

use Illuminate\Support\Facades\Route;
use Modules\Resources\Http\Controllers\Api\V1\SupplierController;
use Modules\Resources\Http\Controllers\ResourcesController;



Route::prefix('resources')->middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('suppliers',SupplierController::class)->names('Resources.suppliers');
});