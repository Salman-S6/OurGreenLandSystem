<?php

namespace Modules\CropManagement\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CropManagement\Models\Crop;

/**
 * Summary of CropFactory
 */
class CropFactory extends Factory
{
     protected $model = Crop::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        return [
            "name" =>[
                "name.ar"=>$this->faker->name,
                "name.en"=>$this->faker->name, 
            ] ,
            "description" =>[
              "description.ar"  => $this->faker->paragraph,
              "description.en"  => $this->faker->paragraph
            ],
            "farmer_id"=>$user->id
        ];
    }
}
