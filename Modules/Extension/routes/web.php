<?php

use Illuminate\Support\Facades\Route;
use Modules\Extension\Http\Controllers\ExtensionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('extensions', ExtensionController::class)->names('extension');
});
