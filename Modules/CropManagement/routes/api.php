<?php

use Illuminate\Support\Facades\Route;
use Modules\CropManagement\Http\Controllers\CropController;
use Modules\CropManagement\Http\Controllers\CropManagementController;



Route::prefix('crops')->group(function(){
    Route::post('/create',[CropController::class,'store'])->name('crop.create');
    Route::post('/update/{crop}',[CropController::class,'update'])->name('crop.update');
    Route::get('/get-all',[CropController::class,'index'])->name('crops.index');
    Route::get('/{crop}',[CropController::class,'show'])->name('crops.show');
    Route::delete('/{crop}',[CropController::class,'destroy'])->name('crop.delete');
});
