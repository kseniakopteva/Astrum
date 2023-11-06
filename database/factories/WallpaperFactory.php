<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallpaper>
 */
class WallpaperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $description = $this->faker->paragraph;

        $title = ucfirst($this->faker->words(rand(1, 5), true));
        $slug = str_replace(" ", "-", $title) . rand();

        return [
            'user_id' => rand(1, 10),
            'name' => $title,
            'slug' => $slug,
            'description' => $description,
            'image' => 'https://source.unsplash.com/random/' . rand(1, 100),
            'price' => rand(10, 30),
        ];
    }
}
