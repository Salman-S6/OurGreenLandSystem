<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first('id')->id,
            'supplier_type' => $this->faker->randomElement(['pesticides', 'fertilizers', 'seeds', 'equipment']),
            'phone_number' => $this->faker->phoneNumber(),
            'license_number' => $this->faker->bothify('LIC-#######'),
        ];
    }
}