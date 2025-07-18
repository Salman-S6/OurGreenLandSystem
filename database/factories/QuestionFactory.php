<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
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

