<?php

namespace Modules\FarmLand\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\Land as ModelsLand;
use Modules\FarmLand\Models\Rehabilitation as ModelsRehabilitation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\FarmLand\Models\Rehabilitation>
 */
class RehabilitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModelsRehabilitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'event' => [
                'ar' => $this->faker->sentence(3, true),
                'en' => $this->faker->sentence(3, true),
            ],
            'description' => [
                'ar' => $this->faker->paragraph(1),
                'en' => $this->faker->paragraph(1),
            ],
            'notes' => [
                'ar' => $this->faker->optional()->sentence(4),
                'en' => $this->faker->optional()->sentence(4),
            ],
        ];
    }
}
