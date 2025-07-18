<?php

namespace Database\Factories;

use App\Models\Crop;
use App\Models\CropPlan;
use App\Models\Land;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CropPlan>
 */
class CropPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CropPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plannedPlanting = $this->faker->dateTimeBetween('-1 month', '+2 months');

        return [
            'crop_id' => Crop::factory(),
            'planned_by' => User::factory(),
            'land_id' => Land::factory(),
            'planned_planting_date' => $plannedPlanting,
            'actual_planting_date' => $this->faker->optional(0.8)->dateTimeBetween($plannedPlanting, '+1 week'),
            'planned_harvest_date' => $this->faker->dateTimeBetween('+3 months', '+6 months'),
            'actual_harvest_date' => $this->faker->optional(0.3)->dateTimeBetween('+3 months', '+7 months'),
            'seed_type' => $this->faker->words(2, true),
            'seed_quantity' => $this->faker->randomFloat(2, 5, 100), // e.g., in kg
            'seed_expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'area_size' => $this->faker->randomFloat(2, 1, 50), // e.g., in hectares
            'status' => $this->faker->randomElement(['active', 'in-progress', 'completed', 'cancelled']),
        ];
    }
}
