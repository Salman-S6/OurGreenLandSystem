<?php

namespace Database\Factories;

use App\Models\InputRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InputRequest>
 */
class InputRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InputRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'approved', 'rejected', 'in-progress', 'delivered', 'received']);
        $isApprovedOrLater = in_array($status, ['approved', 'in-progress', 'delivered', 'received']);

        return [
            'requested_by' => User::factory(),
            'input_type' => $this->faker->randomElement(['seeds', 'fertilizers', 'equipment']),
            'description' => $this->faker->text,
            'quantity' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $status,
            'approved_by' => $isApprovedOrLater ? User::factory() : null,
            'approval_date' => $isApprovedOrLater ? $this->faker->dateTimeThisMonth() : null,
            'delivery_date' => in_array($status, ['Delivered', 'Received']) ? $this->faker->dateTimeThisMonth() : null,
            'notes' => $this->faker->optional()->text,
            'selected_supplier_id' => $isApprovedOrLater ? Supplier::factory() : null,
        ];
    }
}