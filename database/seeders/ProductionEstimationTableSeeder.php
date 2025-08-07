<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\CropManagement\Models\ProductionEstimation;

class ProductionEstimationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('production_estimations')->delete();
        ProductionEstimation::factory(20)->create();
    }
}
