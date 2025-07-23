<?php

namespace Database\Factories;

use App\Models\Land;
use App\Models\Rehabilitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rehabilitation>
 */
class RehabilitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rehabilitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rehabilitationEvents = [
            'Soil Tillage and Aeration',
            'Organic Matter Addition',
            'Cover Cropping Implementation',
            'Afforestation and Tree Planting',
            'Irrigation System Repair and Optimization',
            'Integrated Pest Management Application',
        ];

        return [
            'land_id' => Land::inRandomOrder()->first('id')->id,
            'event' => $this->faker->randomElement($rehabilitationEvents),
            'description' => $this->faker->paragraph,
            'performed_by' => User::inRandomOrder()->first('id')->id,
            'performed_at' => $this->faker->date(),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}