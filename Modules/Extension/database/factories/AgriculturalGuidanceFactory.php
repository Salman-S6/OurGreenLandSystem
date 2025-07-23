<?php

namespace Modules\Extension\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Extension\Models\AgriculturalGuidance;

class AgriculturalGuidanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = AgriculturalGuidance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5),
            'summary' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement(['soil management', 'pest control', 'irrigation', 'crop rotation']),
            'language' => $this->faker->randomElement(['arabic', 'english']),
            'added_by_id' => User::inRandomOrder()->first('id')->id,
            'tags' => $this->faker->words(3),
        ];
    }
}

