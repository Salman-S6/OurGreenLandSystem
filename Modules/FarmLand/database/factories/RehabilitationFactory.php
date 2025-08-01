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
        $rehabilitationEvents = [
            'Soil Tillage and Aeration',
            'Organic Matter Addition',
            'Cover Cropping Implementation',
            'Afforestation and Tree Planting',
            'Irrigation System Repair and Optimization',
            'Integrated Pest Management Application',
        ];

        return [
            'land_id' => ModelsLand::inRandomOrder()->first('id')->id,
            'event' => $this->faker->randomElement($rehabilitationEvents),
            'description' => $this->faker->paragraph,
            'performed_by' => User::inRandomOrder()->first('id')->id,
            'performed_at' => $this->faker->date(),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
