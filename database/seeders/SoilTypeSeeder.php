<?php

namespace Database\Seeders;

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
            [
                'en' => 'Sandy Soil',
                'ar' => 'التربة الرملية'
            ],
            [
                'en' => 'Clay Soil',
                'ar' => 'التربة الطينية'
            ],
            [
                'en' => 'Silt Soil',
                'ar' => 'التربة الغرينية'
            ],
            [
                'en' => 'Loam Soil',
                'ar' => 'التربة الطميية'
            ],
            [
                'en' => 'Chalky Soil',
                'ar' => 'التربة الطباشيرية'
            ],
            [
                'en' => 'Peat Soil',
                'ar' => 'التربة الخثية'
            ],
            [
                'en' => 'Andisol',
                'ar' => 'أنديسول'
            ],
            [
                'en' => 'Red Soil',
                'ar' => 'التربة الحمراء'
            ],
            [
                'en' => 'Black Soil',
                'ar' => 'التربة السوداء'
            ],
            [
                'en' => 'Yellow Soil',
                'ar' => 'التربة الصفراء'
            ],
            [
                'en' => 'Gray Soil',
                'ar' => 'التربة الرمادية'
            ],
            [
                'en' => 'Heavy Clay Soil',
                'ar' => 'التربة الطينية الثقيلة'
            ],
            [
                'en' => 'Light Clay Soil',
                'ar' => 'التربة الطينية الخفيفة'
            ],
            [
                'en' => 'Sandy Clay Loam',
                'ar' => 'الطمي الطيني الرملي'
            ],
            [
                'en' => 'Silty Clay Loam',
                'ar' => 'الطمي الطيني الغريني'
            ],
            [
                'en' => 'Sandy Loam',
                'ar' => 'الطمي الرملي'
            ],
            [
                'en' => 'Sandy Clay',
                'ar' => 'الطين الرملي'
            ],
            [
                'en' => 'Light Sandy Soil',
                'ar' => 'التربة الرملية الخفيفة'
            ],
            [
                'en' => 'Heavy Sandy Soil',
                'ar' => 'التربة الرملية الثقيلة'
            ],
            [
                'en' => 'Alkaline Soil',
                'ar' => 'التربة القلوية'
            ],
            [
                'en' => 'Acidic Soil',
                'ar' => 'التربة الحمضية'
            ],
            [
                'en' => 'Saline Soil',
                'ar' => 'التربة المالحة'
            ],
        ];

        foreach ($soilTypes as $soilType) {
            DB::table('soils')->insert([
                'name' => json_encode($soilType),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
