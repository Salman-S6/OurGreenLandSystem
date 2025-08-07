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

        $routes = Route::getRoutes()->getRoutes();
        $permissions = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            $prefix = $route->getPrefix();

            
            if (!$name || $prefix === "sanctum" || !str_contains($prefix, "api")) {
                continue;
            }

          
            $permissions[] = [
                "name" => $name,
                "guard_name" => "web",
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }

      
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['created_at' => $permission['created_at'], 'updated_at' => $permission['updated_at']]
            );
        }

       
        foreach (UserRoles::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }
    }
}
