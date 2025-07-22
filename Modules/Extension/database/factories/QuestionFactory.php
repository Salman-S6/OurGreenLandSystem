<?php

namespace Modules\Extension\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Extension\Models\Question;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'farmer_id' => User::inRandomOrder()->first('id')->id,
            'title' => rtrim($this->faker->sentence(rand(5, 10)), '.').'?',
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['open', 'closed']),
        ];
    }
}

