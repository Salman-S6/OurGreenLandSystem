<?php

namespace Modules\Extension\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Extension\Models\Answer;
use Modules\Extension\Models\Question;

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
            'expert_id' => User::factory(),
            'question_id' => Question::inRandomOrder()->first('id')->id,
            'answer_text' => $this->translations(['en'] ,[$this->faker->paragraphs(3, true)]),
        ];
    }
}

