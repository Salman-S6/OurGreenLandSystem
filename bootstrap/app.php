<?php

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
<<<<<<< HEAD

        $exceptions->render(function (NotFoundHttpException $e) {
            return ApiResponse::error(
                "Data Not Found!",
                404
            );
        });
        
=======
          $exceptions->render(function (AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to perform this action.',
                ], Response::HTTP_FORBIDDEN);
            }

            return response('Unauthorized action.', Response::HTTP_FORBIDDEN);
        });

>>>>>>> crop-management
    })->create();
