<?php

namespace Modules\FarmLand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FarmLand\Models\Rehabilitation;

class RehabilitationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some fake data for testing
        Rehabilitation::factory()->count(10)->create();
    }
}
