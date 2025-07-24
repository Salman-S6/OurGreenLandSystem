<?php

use Illuminate\Support\Facades\Route;
use Modules\CropManagement\Http\Controllers\CropController;
use Modules\CropManagement\Http\Controllers\CropManagementController;
use Modules\CropManagement\Http\Controllers\CropPlanController;
use Spatie\Permission\Contracts\Role;

Route::prefix('crops')->group(function(){
    Route::post('/create',[CropController::class,'store'])->name('crop.create');
    Route::post('/update/{crop}',[CropController::class,'update'])->name('crop.update');
    Route::get('/get-all',[CropController::class,'index'])->name('crops.index');
    Route::get('/{crop}',[CropController::class,'show'])->name('crops.show');
    Route::delete('/{crop}',[CropController::class,'destroy'])->name('crop.delete');
});



Route::prefix('cropPlan')->group(function(){
    Route::post('/create',[CropPlanController::class,'store'])->name('cropPlan.make');
    Route::post('/update/{cropPlan}',[CropPlanController::class,'update'])->name('cropPlan.update');
    Route::get('/{cropPlan}',[CropPlanController::class,'show'])->name('cropPlan.show');
    Route::get('/all/paln',[CropPlanController::class,'index'])->name('cropPaln.all');
    Route::post('/change-to-cancelled/{cropPlan}',[CropPlanController::class,'switchStatusToCancelled'])->name('cropPlan.cancelled');
    Route::delete('/{cropPlan}',[CropPlanController::class,'destroy'])->name('cropPlan.destroy');
});


