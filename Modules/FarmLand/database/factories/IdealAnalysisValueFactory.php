<?php

namespace Modules\FarmLand\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\FarmLand\Models\IdealAnalysisValue>
 */
class IdealAnalysisValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "type" => $this->faker->randomElement(['soil', 'water']),
            // "crop_id" => Crop::inRandomOrder()->first('id')->id,
            "ph_min" => $this->faker->randomFloat(2, 5.5, 8.5),
            "ph_max" => $this->faker->randomFloat(2, 5.5, 8.5),
            "salinity_min" => $this->faker->randomFloat(2, 0.1, 5),
            "salinity_max" => $this->faker->randomFloat(2, 0.1, 5),
            "fertility_level" => $this->faker->randomElement(['low', 'medium', 'high']),
            "water_quality" => $this->faker->optional()->randomElement(['Good - Clear', 'Fair - Slightly Turbid', 'Poor - High Sediments']) ,
            "notes" => $this->faker->optional(0.5)->paragraph,
        ];
    }
}
