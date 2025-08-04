<?php

use Illuminate\Support\Facades\Route;
use Modules\Resources\Http\Controllers\Api\V1\SupplierController;
use Modules\Resources\Http\Controllers\Api\V1\SupplierRatingController;
use Modules\Resources\Http\Controllers\ResourcesController;


Route::prefix('resources')->middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('suppliers',SupplierController::class)->names('resources.suppliers');
  Route::get('supplier-ratings', [SupplierRatingController::class, 'index'])->name('resources.supplier-ratings.index');
    Route::post('supplier-ratings', [SupplierRatingController::class, 'store'])->name('resources.supplier-ratings.store');
    
    Route::get('supplier-ratings/{rating}', [SupplierRatingController::class, 'show'])->name('resources.supplier-ratings.show');
    Route::put('supplier-ratings/{rating}', [SupplierRatingController::class, 'update'])->name('resources.supplier-ratings.update');
    Route::delete('supplier-ratings/{rating}', [SupplierRatingController::class, 'destroy'])->name('resources.supplier-ratings.destroy');
});