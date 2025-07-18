<?php

namespace Database\Factories;

use App\Models\AgriculturalAlert;
use App\Models\CropPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgriculturalAlert>
 */
class AgriculturalAlertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgriculturalAlert::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->paragraph,
            'crop_plan_id' => CropPlan::inRandomOrder()->first('id')->id,
            'alert_level' => $this->faker->randomElement(['info', 'warning']),
            'alert_type' => $this->faker->randomElement(['weather', 'general', 'fertilization', 'pest', 'irrigation']),
            'send_time' => $this->faker->dateTimeThisMonth(),
            'created_by' => User::inRandomOrder()->first('id')->id,
        ];
    }
}