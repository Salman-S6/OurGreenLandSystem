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
        $this->call(
            RolesAndPermissionsSeeder::class
        );
        
        ProductionEstimation::factory()
            ->count(200)
            ->create();
    }
}
