<?php

namespace Modules\FarmLand\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\Rehabilitation;

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
    protected $model = Rehabilitation::class;

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

    public function configure()
    {
        return $this->afterCreating(function (Rehabilitation $rehab) {
            $lands = Land::inRandomOrder()->take(rand(50, 80))->pluck('id');
            $user_id = User::inRandomOrder()->first()->id;

            foreach ($lands as $landId) {
                $rehab->lands()->attach($landId, [
                    'performed_by' => $user_id, 
                    'performed_at' => now()->subDays(rand(1, 365)),
                ]);
            }
        });
    }
}
