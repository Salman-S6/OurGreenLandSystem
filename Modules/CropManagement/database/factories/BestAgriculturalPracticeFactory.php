<?php

namespace Modules\CropManagement\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\BestAgriculturalPractice;
use Modules\CropManagement\Models\CropGrowthStage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BestAgriculturalPractice>
 */
class BestAgriculturalPracticeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BestAgriculturalPractice::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'growth_stage_id' => CropGrowthStage::factory(),
            'practice_type' => $this->faker->randomElement(['irrigation', 'fertilization', 'pest-control']),
            'material' => $this->faker->words(2, true),
            'quantity' => $this->faker->randomFloat(2, 5, 100),
            'application_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
