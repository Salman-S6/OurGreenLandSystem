<?php

namespace Modules\FarmLand\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\SoilAnalysis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\FarmLand\Models\SoilAnalysis>
 */
class SoilAnalysisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model =  SoilAnalysis::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'land_id' =>  Land::inRandomOrder()->first('id')->id,
            'performed_by' => User::inRandomOrder()->first('id')->id,
            'sample_date' => $this->faker->date(),
            'ph_level' => $this->faker->randomFloat(2, 5.5, 8.5),
            'salinity_level' => $this->faker->randomFloat(2, 0, 8.0),
            'fertility_level' => $this->faker->randomElement(['high', 'medium', 'low']),
            'nutrient_content' => json_encode([
                'Nitrogen (N)' => $this->faker->randomFloat(2, 10, 50) . ' ppm',
                'Phosphorus (P)' => $this->faker->randomFloat(2, 5, 25) . ' ppm',
                'Potassium (K)' => $this->faker->randomFloat(2, 50, 200) . ' ppm',
            ]),
            'contaminants' => $this->faker->optional(0.3)->randomElement(['Heavy Metals (trace)', 'Pesticide residue (low)', 'None detected']),
            'recommendations' => $this->faker->optional(0.8)->paragraph,
        ];
    }
}
