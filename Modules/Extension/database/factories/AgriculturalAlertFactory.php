<?php

namespace Modules\Extension\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\CropPlan;
use Modules\Extension\Models\AgriculturalAlert;

class AgriculturalAlertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = AgriculturalAlert::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->translations(['en'], [$this->faker->sentence(4)]),
            'message' => $this->translations(['en'], [$this->faker->paragraph]),
            'crop_plan_id' => CropPlan::factory(),
            'alert_level' => $this->faker->randomElement(['info', 'warning']),
            'alert_type' => $this->faker->randomElement(['weather', 'general', 'fertilization', 'pest', 'irrigation']),
            'send_time' => $this->faker->dateTimeThisMonth(),
            'created_by' => User::factory(),
        ];
    }
}

