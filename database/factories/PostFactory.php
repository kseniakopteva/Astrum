<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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

        $title = ucfirst($this->faker->words(rand(1, 7), true));
        $slug = str_replace(" ", "-", $title);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $sentence,
            'body' => $body,
        ];
    }
}
