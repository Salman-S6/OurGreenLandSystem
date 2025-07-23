<?php

namespace Modules\Extension\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Extension\Models\Answer;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'expert_id' => User::inRandomOrder()->first('id')->id,
            'question_id' => Question::inRandomOrder()->first('id')->id,
            'answer_text' => $this->faker->paragraphs(3, true),
        ];
    }
}

