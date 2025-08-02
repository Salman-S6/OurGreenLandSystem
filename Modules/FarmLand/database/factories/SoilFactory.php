<?php

namespace Modules\FarmLand\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\Soil;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\FarmLand\Models\Soil>
 */
class SoilFactory extends Factory
{
    protected $model = Soil::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=> $this->faker->name,
        ];
    }
}