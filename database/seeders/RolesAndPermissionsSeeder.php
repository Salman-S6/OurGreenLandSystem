<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $routes = Route::getRoutes()->getRoutes();
        $permissions = [];

        foreach ($routes as $route) {
            if ($route->getPrefix() !== "sanctum" && str_contains($route->getPrefix(), "api"))
                $permissions[] = [
                    "name" => $route->getName(),
                    "guard_name" => "web",
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
        }

        Permission::insert($permissions);


        foreach (UserRoles::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }

        $superAdmin = User::create([
            "name" => "x4",
            "email" => "x4@gmail.com",
            "password" => Hash::make("x4123")
        ]);

        $superAdmin->assignRole(UserRoles::SuperAdmin);

    }
}
