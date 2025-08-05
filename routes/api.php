
<?php

use App\Enums\AttachableModels;
use App\Http\Controllers\Api\V1\AttachmentController;
use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\AuthorizationController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * this route will be left here only for testing purposes
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')
    ->name('auth.user');

/**
 *
 *
 * --------------------------------------------------------------
 * AuthenticationController routes
 * ---------------------------------------------------------------
 */
Route::post("/login", [AuthenticationController::class, "login"])
    ->name("login");
Route::post("/sign-up", [AuthenticationController::class, "register"])
    ->name("register");
Route::post("/logout", [AuthenticationController::class, "logout"])
    ->middleware("auth:sanctum")
    ->name("logout");

Route::post('/forgot-password', [AuthenticationController::class, 'sendResetLinkEmail'])
   ->name("password.forgot");
   
Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])
   ->name("password.request");


Route::post('/email/resend', [AuthenticationController::class, 'resendVerificationEmail'])
    ->middleware('auth:sanctum')
    ->name("verification.resend-email");

Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verify'])
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');


Route::middleware(["auth:sanctum", "throttle:api"])->group(function () {
    /**
     *
     *
     * ----------------------------------------------------------------
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

        Route::delete('/permissions', [AuthorizationController::class,'removePermissionsFromRole'])
            ->name('roles.permissions.remove');

    });

    Route::prefix('/users/{user}')->group(function () {

        Route::post('/roles', [AuthorizationController::class,'assginRolesToUser'])
            ->name('users.roles.assgin');

        Route::delete('/roles', [AuthorizationController::class,'removeRolesFromUser'])
            ->name('users.roles.remove');

        Route::post('/permissions', [AuthorizationController::class,'assginPermissionsToUser'])
            ->name('users.permissions.assgin');

        Route::delete('/permissions', [AuthorizationController::class,'removePermissionsFromUser'])
            ->name('users.permissions.remove');
    });

    /**
     *
     *
     *
     * ----------------------------------------------------------------
     * UserController routes.
     * -----------------------------------------------------------------
    */

    Route::apiResource('users', UserController::class);
    /**
     *
     *
     *
     * ----------------------------------------------------------------
     * AttachmentController routes.
     * -----------------------------------------------------------------
    */
    Route::prefix("{attachable}/{attachable_id}/")->group(function() {
        Route::get("attachments", [AttachmentController::class, "index"])->name("attachments.index");
        Route::post("attachments", [AttachmentController::class, "store"])
            ->middleware("throttle:uploads")
            ->name("attachments.store");
    })->whereIn("attachable", AttachableModels::slugs());

    Route::delete("attachments/{attachment}", [AttachmentController::class,"destroy"])
        ->name("attachments.destroy");
});
