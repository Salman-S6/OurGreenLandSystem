<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use Illuminate\Database\Seeder;
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

        /**
         * here, i used route names as permissions so i could add them dynamically
         * by only running the seeder.
         */
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
            $role = Role::create(["name" => $role->value]);
        }

    }
}
