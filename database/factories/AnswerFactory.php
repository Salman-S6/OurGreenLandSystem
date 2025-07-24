<?php

namespace Database\Factories;



use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Extension\Models\Answer;
use Modules\Extension\Models\Question;

/**
 * Summary of AnswerFactory
 */
class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
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