<?php

use Illuminate\Support\Facades\Route;
use Modules\Farmland\Http\Controllers\FarmlandController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('farmlands', FarmlandController::class)->names('farmland');
});
