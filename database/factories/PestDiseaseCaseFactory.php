<?php

namespace Database\Factories;

use App\Models\CropPlan;
use App\Models\PestDiseaseCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PestDiseaseCase>
 */
class PestDiseaseCaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PestDiseaseCase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crop_plan_id' => CropPlan::factory(),
            'reported_by' => User::factory(),
            'case_type' => $this->faker->randomElement(['pest', 'disease']),
            'case_name' => $this->faker->words(2, true),
            'severity' => $this->faker->randomElement(['high', 'medium', 'low']),
            'description' => $this->faker->paragraph,
            'discovery_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'location_details' => $this->faker->sentence,
        ];
    }
}