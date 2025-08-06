<?php

namespace Modules\Extension\Database\Factories;

use App\Models\User;
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
            'title' => $this->translations(['en'], [$this->faker->sentence()]),
            'summary' => $this->translations(['en'], [$this->faker->paragraph(3)]),
            'category' => $this->translations(
                'en', 
                $this->faker->randomElement([
                    'soil management', 'pest control', 'irrigation', 'crop rotation'
                ])),
            'added_by_id' => User::factory(),
            'tags' => $this->faker->words(3),
        ];
    }
}

