<?php

namespace Modules\CropManagement\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\CropPlan;
use Modules\CropManagement\Models\ProductionEstimation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionEstimation>
 */
class ProductionEstimationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionEstimation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crop_plan_id' => CropPlan::inRandomOrder()->first('id')->id,
            'expected_quantity' => $this->faker->randomFloat(2, 500, 10000),
            'estimation_method' => $this->faker->sentence(),
            'actual_quantity' => $this->faker->optional(0.7)->randomFloat(2, 450, 11000),
            'crop_quality' => $this->faker->optional(0.7)->randomElement(['excellent', 'average', 'poor']),
            'reported_by' => User::inRandomOrder()->first('id')->id,
            'notes' => $this->faker->optional()->paragraph,
        ];
    }
}
