<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\CropManagement\Models\ProductionEstimation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        ProductionEstimation::factory()
            ->count(200)
            ->create();
            
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserTableSeeder::class,
            SoilTypeSeeder::class,
            CropTableSeeder::class,
            LandTableSeeder::class,
            CropPlanTableSeeder::class,
            SupplierTableSeeder::class,
        ]);
    }
}
