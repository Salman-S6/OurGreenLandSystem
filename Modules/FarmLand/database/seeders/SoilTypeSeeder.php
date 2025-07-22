namespace Database\Seeders;
<?php 

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
            ['en' => 'Sandy Soil', 'ar' => 'تربة رملية'],
            ['en' => 'Clay Soil', 'ar' => 'تربة طينية'],
            ['en' => 'Silt Soil', 'ar' => 'تربة غرينية'],
            ['en' => 'Loam Soil', 'ar' => 'تربة طميية'],
            ['en' => 'Chalky Soil', 'ar' => 'تربة طباشيرية'],
            ['en' => 'Peat Soil', 'ar' => 'تربة خثية'],
            ['en' => 'Andisol', 'ar' => 'تربة أنديزول'],
            ['en' => 'Red Soil', 'ar' => 'تربة حمراء'],
            ['en' => 'Black Soil', 'ar' => 'تربة سوداء'],
            ['en' => 'Yellow Soil', 'ar' => 'تربة صفراء'],
            ['en' => 'Gray Soil', 'ar' => 'تربة رمادية'],
            ['en' => 'Heavy Clay Soil', 'ar' => 'تربة طينية ثقيلة'],
            ['en' => 'Light Clay Soil', 'ar' => 'تربة طينية خفيفة'],
            ['en' => 'Sandy Clay Loam', 'ar' => 'تربة رملية طينية'],
            ['en' => 'Silty Clay Loam', 'ar' => 'تربة غرينية طينية'],
            ['en' => 'Sandy Loam', 'ar' => 'تربة رملية طميية'],
            ['en' => 'Sandy Clay', 'ar' => 'تربة رملية طينية'],
            ['en' => 'Light Sandy Soil', 'ar' => 'تربة رملية خفيفة'],
            ['en' => 'Heavy Sandy Soil', 'ar' => 'تربة رملية ثقيلة'],
            ['en' => 'Alkaline Soil', 'ar' => 'تربة قلوية'],
            ['en' => 'Acidic Soil', 'ar' => 'تربة حمضية'],
            ['en' => 'Saline Soil', 'ar' => 'تربة مالحة'],
        ];

        foreach ($soilTypes as $name) {
            DB::table('soils')->insert([
                'name' => json_encode($name), // Translatable field
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
