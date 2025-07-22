<?php

namespace Modules\FarmLand\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SoilTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soilTypes = [
            'Sandy Soil',
            'Clay Soil',
            'Silt Soil',
            'Loam Soil',
            'Chalky Soil',
            'Peat Soil',
            'Andisol',
            'Red Soil',
            'Black Soil',
            'Yellow Soil',
            'Gray Soil',
            'Heavy Clay Soil',
            'Light Clay Soil',
            'Sandy Clay Loam',
            'Silty Clay Loam',
            'Sandy Loam',
            'Sandy Clay',
            'Light Sandy Soil',
            'Heavy Sandy Soil',
            'Alkaline Soil',
            'Acidic Soil',
            'Saline Soil',
        ];


        foreach ($soilTypes as $name) {
            DB::table('soils')->insert([
                'name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
