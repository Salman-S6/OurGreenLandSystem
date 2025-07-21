<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('farmlands', Controller::class)->names('farmland');

});
