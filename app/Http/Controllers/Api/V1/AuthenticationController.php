<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        if (Auth::attempt($credentials))
            return ApiResponse::error("Wrong Credentials.", 401);
        
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken("api-token")->plainTextToken;

        return ApiResponse::success([
            "user" => $user,
            "token"=> $token
        ], "logged in.");
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string",
                "email"=> "required|email|unique:users,email",
                "password"=> "required|password_confirmed|min:8|max:50",
            ]);

            if ($validator->fails()) {
                return ApiResponse::error(
                    "" ,
                    400,
                    ["errors" => $validator->errors()]
                );
            }

            $user = User::create($validator->validated());
            $token = $user->createToken("api-token")->plainTextToken;

            return ApiResponse::success([
                "user"=> $user,
                "token"=> $token
            ], "registerd successfully, welcome");

        } catch (Exception $e) {
            return ApiResponse::error(
                "some", 
                500,
                ["errors" => $e->getMessage()]
            );
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(
            message: "Logged out successfully.",
        );
    }
}
