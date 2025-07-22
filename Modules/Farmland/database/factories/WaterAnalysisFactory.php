<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\WaterAnalysis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WaterAnalysis>
 */
class WaterAnalysisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WaterAnalysis::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'land_id' => Land::inRandomOrder()->first()->id,
            'performed_by' => User::inRandomOrder()->first()->id,
            'sample_date' => $this->faker->date(),
            'ph_level' => $this->faker->randomFloat(2, 6.0, 8.5),
            'salinity_level' => $this->faker->randomFloat(2, 0.1, 5.0),
            'water_quality' => $this->faker->optional()->randomElement(['Good - Clear', 'Fair - Slightly Turbid', 'Poor - High Sediments']),
            'suitability' => $this->faker->randomElement(['suitable', 'limited', 'unsuitable']),
            'contaminants' => $this->faker->optional(0.4)->randomElement(['Nitrates (low)', 'Heavy metals (trace)', 'High algae content', 'None detected']),
            'recommendations' => $this->faker->optional(0.7)->paragraph,
        ];
    }
}
