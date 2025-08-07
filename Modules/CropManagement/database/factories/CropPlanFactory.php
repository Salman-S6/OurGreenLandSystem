<?php

namespace Modules\CropManagement\Database\Factories;

use Modules\FarmLand\Models\Land;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\Crop;
use Modules\CropManagement\Models\CropPlan;

/**
 * Summary of CropPlanFactory
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

    $actualPlanting = $this->faker->optional(0.8)->dateTimeBetween(
        $plannedPlanting,
        (clone $plannedPlanting)->modify('+1 week')
    );


    $plannedHarvest = $this->faker->dateTimeBetween(
        (clone $plannedPlanting)->modify('+3 months'),
        (clone $plannedPlanting)->modify('+6 months')
    );

   
    $actualHarvest = null;
    if ($actualPlanting) {
        $actualHarvest = $this->faker->optional(0.3)->dateTimeBetween(
            (clone $actualPlanting)->modify('+3 months'),
            (clone $actualPlanting)->modify('+7 months')
        );
    }

    return [
        'crop_id' => Crop::factory(),
        'planned_by' => User::factory(),
        'land_id' => Land::factory(),
        'planned_planting_date' => $plannedPlanting,
        'actual_planting_date' => $actualPlanting,
        'planned_harvest_date' => $plannedHarvest,
        'actual_harvest_date' => $actualHarvest,
        'seed_type' => $this->faker->words(2, true),
        'seed_quantity' => $this->faker->randomFloat(2, 5, 100),
        'seed_expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
        'area_size' => $this->faker->randomFloat(2, 1, 50),
        'status' => $this->faker->randomElement(['active', 'in-progress', 'completed', 'cancelled']),
    ];
}

}
