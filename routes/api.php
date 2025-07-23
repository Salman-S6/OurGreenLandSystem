
<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\AuthorizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * this route will be left here only for testing purposes
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/login", [AuthenticationController::class, "login"])
    ->name("login");
Route::post("/sign-up", [AuthenticationController::class, "register"])
    ->name("register");
Route::post("/logout", [AuthenticationController::class, "logout"])
    ->middleware("auth:sanctum")
    ->name("logout");

Route::middleware(["auth:sanctum", "throttle:api"])->group(function () {
    
    /**
     * AuthorizationController routes.
     * ----------------------------------------------------------------
     */
    Route::get('/roles/permissions', [AuthorizationController::class, 'all'])
        ->name('roles.permissions.all');
    
    Route::prefix('/roles/{role}')->group(function () {
    
        Route::get('/permissions', [AuthorizationController::class, 'index'])
            ->name('roles.permissions.index');
    
        Route::post('/permissions', [AuthorizationController::class,'assginPermissionsToRole'])
            ->name('roles.permissions.assgin');
    
        Route::delete('/permissions', [AuthorizationController::class,'removePermissionsToRole'])
            ->name('roles.permissions.remove');
    
    });
    
    Route::prefix('/users/{user}')->group(function () {
    
        Route::post('/roles', [AuthorizationController::class,'assginRolesToUser'])
            ->name('users.roles.assgin');
    
        Route::post('/roles', [AuthorizationController::class,'removeRolesFromUser'])
            ->name('users.roles.remove');
    
        Route::post('/permissions', [AuthorizationController::class,'assginPermissionsToUser'])
            ->name('users.permissions.assgin');
    
        Route::delete('/permissions', [AuthorizationController::class,'removePermissionsFromUser'])
            ->name('users.permissions.remove');
    
    });
    /** --------------------------------------------------------------- */
});