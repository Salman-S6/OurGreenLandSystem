<?php

namespace Modules\Extension\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Extension\Models\AgriculturalAlert;
use Modules\Extension\Models\AgriculturalGuidance;
use Modules\Extension\Models\Answer;
use Modules\Extension\Models\Question;

class ExtensionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        AgriculturalGuidance::factory()->count(10)->create();
        AgriculturalAlert::factory()->count(10)->create();
        Question::factory()->count(30)->create();
        Answer::factory()->count(50)->create();
    }
}
