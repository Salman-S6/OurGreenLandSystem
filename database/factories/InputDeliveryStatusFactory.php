<?php

namespace Database\Factories;

use App\Models\InputDeliveryStatus;
use App\Models\InputRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InputDeliveryStatus>
 */
class InputDeliveryStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InputDeliveryStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actionType = $this->faker->randomElement(['approved', 'rejected']);

        return [
            'input_request_id' => InputRequest::inRandomOrder()->first('id')->id,
            'action_by' => User::inRandomOrder()->first('id')->id,
            'action_type' => $actionType,
            'rejection_reason' => $actionType === 'rejected' ? $this->faker->sentence() : null,
            'notes' => $this->faker->optional()->text,
            'action_date' => $this->faker->dateTimeThisMonth(),
        ];
    }
}