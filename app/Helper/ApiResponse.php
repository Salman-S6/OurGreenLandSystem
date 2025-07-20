<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse {
    public static function success(array $data = [], string $message = "job done successfully", int $code = 200): JsonResponse
    {
        return response()->json([
            "status" => true,
            "message" => $message,
            "data" => $data,
        ], $code);
    }

    public static function error(string $message = "", int $code = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            "status" => false,
            "message" => $message,
            $errors
        ], $code);
    }
}