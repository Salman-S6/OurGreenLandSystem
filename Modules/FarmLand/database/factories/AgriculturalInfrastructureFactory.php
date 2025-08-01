<?php

namespace Modules\FarmLand\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\AgriculturalInfrastructure;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\FarmLand\Models\AgriculturalInfrastructure>
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
