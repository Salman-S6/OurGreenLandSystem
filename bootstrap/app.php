<?php

use App\Exceptions\CrudException;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (NotFoundHttpException $e) {
            return ApiResponse::error(
                "Data Not Found.",
                404
            );
        });

        $exceptions->render(function (InvalidSignatureException $e) {
            return ApiResponse::error(
                "Invalid Signature",
                403
            );
        });

        $exceptions->render(function (AuthorizationException $e) {
            return ApiResponse::error(
                "Unauthorized Action",
                403
            );
        });
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            return ApiResponse::error(
                "Unauthorized Action",
                403
            );
        });

        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::error(
                "Unauthenticated.",
                401
            );
        });

        $exceptions->render(function (ThrottleRequestsException $e) {
            return ApiResponse::error(
                "Too many requests.",
                429
            );
        });

        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::error(
                'Validation failed.',
                422,
                $e->errors()
            );
        });


        $exceptions->render(function (CrudException $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode());
        });
    })->create();
