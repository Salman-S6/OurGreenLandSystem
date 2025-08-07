<?php

namespace Modules\CropManagement\Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\CropGrowthStage;
use Modules\CropManagement\Models\CropPlan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\CropManagement\Models\CropGrowthStage>
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
            'crop_plan_id' => CropPlan::factory(),
            'start_date' => $startDate,
            'end_date' => $startDate,
            'name' => $this->faker->randomElement(['seeding', 'germination', 'vegetative-growth', 'flowering', 'fruiting', 'harvesting']),
            'notes' => $this->faker->optional()->paragraph,
            // 'recorded_by' => User::factory(),
            'recorded_by' => User::inRandomOrder()->first()->id,
        ];
    }
}
