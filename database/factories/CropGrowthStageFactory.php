<?php

namespace Database\Factories;

use App\Models\CropGrowthStage;
use App\Models\CropPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CropGrowthStage>
 */
class CropGrowthStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CropGrowthStage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'crop_plan_id' => CropPlan::inRandomOrder()->first('id')->id,
            'start_date' => $startDate,
            'end_date' => $this->faker->optional(0.8)->dateTimeBetween($startDate, '+2 weeks'),
            'name' => $this->faker->randomElement(['seeding', 'germination', 'vegetative-growth', 'flowering', 'fruiting', 'harvesting']),
            'notes' => $this->faker->optional()->paragraph,
            'recorded_by' => User::inRandomOrder()->first('id')->id,
        ];
    }
}