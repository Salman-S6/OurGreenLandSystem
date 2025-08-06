<?php

namespace Modules\CropManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\Crop;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\CropManagement\Models\Crop>
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
            "name" => $this->faker->name,
            "description" => $this->faker->paragraph
        ];
    }
}
