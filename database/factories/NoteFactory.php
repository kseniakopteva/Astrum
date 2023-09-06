<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $body = $this->faker->paragraph;
        $sentence = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1', $body); // first sentence

        $slug = substr(str_replace(" ", "-", $sentence), 0, -1);

        return [
            'user_id' => User::factory(),
            'slug' => $slug,
            'notebody' => $body,
        ];
    }
}
