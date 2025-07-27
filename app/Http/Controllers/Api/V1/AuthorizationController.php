<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authorization\AssginPermissionsToUserRequest;
use App\Http\Requests\Authorization\AssginPermissionsToRoleRequest;
use App\Http\Requests\Authorization\AssginRolesToUserRequest;
use App\Http\Requests\Authorization\RemovePermissionsFromRole;
use App\Http\Requests\Authorization\RemovePermissionsFromUser;
use App\Http\Requests\Authorization\RemoveRolesFromUser;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthorizationController extends Controller
{
    /**
     * return all roles and thier permissions.
     * @return \Illuminate\Http\JsonResponse
     */
    public function all() {
        try {
            Gate::authorize("roles-permissions-crud");

            $roles = Cache::rememberForever("roles-permissions", function () {
                return Role::with("permissions")->get();
            });

            return ApiResponse::success([
                "roles" => $roles
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Role $role)
    {
        try {
            Gate::authorize("roles-permissions-crud");

            $role = Cache::rememberForever("role_{$role->id}", function () use ($role) {
                return $role->load("permissions");
            }); 

            return ApiResponse::success([
                "role" => $role,
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }

    }

    /**
     * assgin new permissions to a specific role.
     */
    public function assginPermissionsToRole(AssginPermissionsToRoleRequest $request, Role $role)
    {
        try {
            $role->givePermissionTo($request->permissions);
            $role->load("permissions");

            Cache::set("role_{$role->id}", $role, 3600);
            Cache::forget("roles-permissions");

            return ApiResponse::success([
                "role"=> $role,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }

    /**
     * assgin new permissions to a specific user.
     */
    public function assginPermissionsToUser(AssginPermissionsToUserRequest $request, User $user)
    {
        try {
            $user->givePermissionTo($request->permissions);
            $user->load("permissions");

            return ApiResponse::success([
                "user"=> $user,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }

    /**
     * assgin new roles to a specific user.
     */
    public function assginRolesToUser(AssginRolesToUserRequest $request, User $user)
    {
        try {
            $user->assignRole($request->roles);
            $user->load("roles", "permissions");

            return ApiResponse::success([
                "user"=> $user,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }

    /**
     * remove roles from a specific user.
     */
    public function removeRolesFromUser(RemoveRolesFromUser $request, User $user)
    {
        try {
            $user->removeRole($request->roles);
            $user->load("roles", "permissions");

            return ApiResponse::success([
                "user"=> $user,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }

    /**
     * remove permissions from a specific role.
     */
    public function removePermissionsFromRole(RemovePermissionsFromRole $request, Role $role)
    {
        try {
            $role->revokePermissionTo($request->permissions);
            $role->load("roles", "permissions");

            Cache::set("role_{$role->id}", $role, 3600);
            Cache::forget("roles-permissions");

            return ApiResponse::success([
                "role"=> $role,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }

    /**
     * remove permissions from a specific user.
     */
    public function removePermissionsFromUser(RemovePermissionsFromUser $request, User $user)
    {
        try {
            $user->revokePermissionTo($request->permissions);
            $user->load("roles", "permissions");

            return ApiResponse::success([
                "user"=> $user,
            ]);
        } catch (Exception $e) {
            return ApiResponse::error(code: 500);
        }
    }
}
