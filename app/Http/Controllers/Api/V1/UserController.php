<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\MailTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Gate::authorize("viewAny", User::class);
            
            // discuss with team, what relations we should eager load.
            $users = Cache::rememberForever("users", function () {
                return User::with("roles", "permissions")->get();
            });

            return ApiResponse::success([
                "users"=> $users
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create(
                $request->validated());

            MailJob::dispatch(MailTypes::VerificationMail, $user);

            Cache::forget("users");

            return ApiResponse::success([
                "user" => $user
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code:500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            Gate::authorize("view", [User::class, $user]);

            // discuss with team, what relations we should eager load.
            $user = Cache::rememberForever("user_{$user->id}", function () use ($user) {
                return $user->load("roles", "permissions");
            });

            return ApiResponse::success([
                "user"=> $user
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code:500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            Gate::authorize("update", [User::class, $user]);

            $user->update($request->validated());
            
            Cache::forget("users");
            Cache::forget("user_{$user->id}");

            if ($request->has("email"))
            {
                $user->update([
                    "email_verified_at" => null
                ]);
                MailJob::dispatch(MailTypes::VerificationMail, $user);
            }
    
            return ApiResponse::success([
                "user"=> $user
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code:500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            Gate::authorize("delete", [User::class, $user]);
    
            $user->delete();
    
            Cache::forget("users");
            Cache::forget("user_{$user->id}");
    
            return ApiResponse::success([
                "user"=> $user
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code:500);
        }
    }
}
