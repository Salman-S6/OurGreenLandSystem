<?php

namespace Database\Factories;

use App\Models\AgriculturalInfrastructure;
use App\Models\Land;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgriculturalInfrastructure>
 */
class AgriculturalInfrastructureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgriculturalInfrastructure::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['irrigationSystem', 'greenhouse', 'storage']),
            'status' => $this->faker->randomElement(['functional', 'damaged']),
            'description' => $this->faker->optional()->paragraph,
            'installation_date' => $this->faker->optional()->date(),
        ];
    }
}