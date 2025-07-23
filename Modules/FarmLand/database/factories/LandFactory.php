<?php

namespace Database\Factories;

use App\Models\Soil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Land>
 */
class LandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $centerLat = $this->faker->latitude();
        $centerLng = $this->faker->longitude();

        return [
            'user_id' => User::inRandomOrder()->first('id')->id,
            'farmer_id' => User::inRandomOrder()->first('id')->id,
            'area' => $this->faker->randomFloat(2, 1, 500),
            'soil_type_id' => Soil::inRandomOrder()->first('id')->id,
            'damage_level' => $this->faker->randomElement(['low', 'medium', 'high']),
            'gps_coordinates' => [
                'latitude' => $centerLat,
                'longitude' => $centerLng,
            ],
            'boundary_coordinates' => [
                ['lat' => $centerLat + 0.01, 'lng' => $centerLng + 0.01],
                ['lat' => $centerLat + 0.01, 'lng' => $centerLng - 0.01],
                ['lat' => $centerLat - 0.01, 'lng' => $centerLng - 0.01],
                ['lat' => $centerLat - 0.01, 'lng' => $centerLng + 0.01],
                ['lat' => $centerLat + 0.01, 'lng' => $centerLng + 0.01],
            ],
            'rehabilitation_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
        ];
    }
}
