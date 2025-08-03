<?php

use Illuminate\Support\Facades\Route;
use Modules\Resources\Http\Controllers\Api\V1\InputRequestController;
use Modules\Resources\Http\Controllers\ResourcesController;

Route::middleware(['auth:sanctum'])->prefix('input-request')->group(function () {
 
 Route::post('/create',[InputRequestController::class,'store'])->name('request.store');
  Route::post('/update/{inputRequest}',[InputRequestController::class,'update'])->name('request.update');
  Route::get('/get/{inputRequest}',[InputRequestController::class,'show'])->name('request.show');
  Route::get('/all',[InputRequestController::class,'index'])->name('request.all');
  Route::delete('/{inputRequest}',[InputRequestController::class,'destroy'])->name('request.delete');

});
