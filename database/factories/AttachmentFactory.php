<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attachable = $this->faker->randomElement([null, 'App\Models\InputRequest', 'App\Models\User']);
        return [
             'uploaded_by' => User::inRandomOrder()->first('id')->id,
             'path' => $this->faker->filePath(),
             'url' => $this->faker->url(),
             'disk' => 'public',
             'attachable_id' => $attachable ? $this->faker->randomNumber() : null,
             'attachable_type' => $attachable,
             'file_name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
             'file_size' => $this->faker->numberBetween(100, 1000000),
             'mime_type' => $this->faker->mimeType(),
        ];
    }
}
