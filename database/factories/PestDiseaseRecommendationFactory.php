<?php

namespace Database\Factories;

use App\Models\PestDiseaseCase;
use App\Models\PestDiseaseRecommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PestDiseaseRecommendation>
 */
class PestDiseaseRecommendationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PestDiseaseRecommendation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pest_disease_case_id' => PestDiseaseCase::inRandomOrder()->first('id')->id,
            'recommendation_name' => $this->faker->bs(),
            'recommended_dose' => $this->faker->numerify('##ml per ##L water'),
            'application_method' => $this->faker->sentence(),
            'safety_notes' => $this->faker->optional()->paragraph,
            'recommended_by' => User::inRandomOrder()->first('id')->id,
        ];
    }
}