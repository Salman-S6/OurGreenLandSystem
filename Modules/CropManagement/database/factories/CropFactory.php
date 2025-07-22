<?php

namespace Modules\CropManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\Crop;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crop>
 */
class CropFactory extends Factory
{
    protected $model = Crop::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => [
                'en' => $this->faker->unique()->word,
                'ar' => $this->faker->unique()->word,
            ],
            "description" =>[
               'en' => $this->faker->paragraph,
               'ar' => $this->faker->paragraph,
            ]
        ];
    }
}
