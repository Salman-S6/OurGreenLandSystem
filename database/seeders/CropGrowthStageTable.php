<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\CropManagement\Models\CropGrowthStage;

class CropGrowthStageTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CropGrowthStage::factory(10)->create();
    }
}
