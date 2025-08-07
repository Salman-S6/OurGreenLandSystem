<?php

use Illuminate\Support\Facades\Route;
use Modules\Resources\Http\Controllers\Api\V1\SupplierController;
use Modules\Resources\Http\Controllers\Api\V1\SupplierRatingController;
use Modules\Resources\Http\Controllers\Api\V1\InputRequestController;
use Modules\Resources\Http\Controllers\ResourcesController;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('resources')->group(function () {

  /**
   *----------------------------------------------------------------
   * InputRequestController routes.
   * ----------------------------------------------------------------
   */
  Route::prefix('input-request')->group(function(){
      Route::post('/create', [InputRequestController::class, 'store'])->name('resources.request.store');

      Route::post('/update/{inputRequest}', [InputRequestController::class, 'update'])->name('resources.request.update');

      Route::get('/get/{inputRequest}', [InputRequestController::class, 'show'])->name('resources.request.show');

      Route::get('/all', [InputRequestController::class, 'index'])->name('resources.request.all');

      Route::delete('/{inputRequest}', [InputRequestController::class, 'destroy'])->name('resources.request.delete');

   });

  /**
   * Supplier Route
   */
  Route::apiResource('suppliers', SupplierController::class)->names('resources.suppliers');

  /**
   * Supplier Rating Route
   */
  Route::get('supplier-ratings', [SupplierRatingController::class, 'index'])->name('resources.supplier-ratings.index');
  Route::post('supplier-ratings', [SupplierRatingController::class, 'store'])->name('resources.supplier-ratings.store');

  Route::get('supplier-ratings/{rating}', [SupplierRatingController::class, 'show'])->name('resources.supplier-ratings.show');
  Route::put('supplier-ratings/{rating}', [SupplierRatingController::class, 'update'])->name('resources.supplier-ratings.update');
  Route::delete('supplier-ratings/{rating}', [SupplierRatingController::class, 'destroy'])->name('resources.supplier-ratings.destroy');
});
