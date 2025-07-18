<?php

namespace Database\Factories;

use App\Models\AgriculturalGuidance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgriculturalGuidance>
 */
class AgriculturalGuidanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgriculturalGuidance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
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