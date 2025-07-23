<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|string"
        ]);

        if ($validator->fails())
            return ApiResponse::error("Wrong data provided", 400, ["errors" => $validator->errors()]);

        if (!Auth::attempt($validator->validated()))
            return ApiResponse::error("wrong email or password.", 404);

        $user = Auth::user();
        $token = $user->createToken("api-token")->plainTextToken;

        return ApiResponse::success([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed|min:8|max:50",
        ]);

        if ($validator->fails())
            return ApiResponse::error("Wrong data provided", 400, ["errors" => $validator->errors()]);

        try {
            $user = User::create($validator->validated());
            $token = $user->createToken("api-token")->plainTextToken;
            
            return ApiResponse::success([
                "user" => $user,
                "token" => $token
            ], "user created successfully.", 201);

        } catch (Exception $e) {
            return ApiResponse::error("failed to create user.", 500);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success();
    }

}
