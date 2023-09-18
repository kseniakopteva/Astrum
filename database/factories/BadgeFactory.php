<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            'slate', 'red', 'orange', 'amber',
            'yellow', 'lime', 'green', 'emerald',
            'teal', 'cyan', 'sky', 'blue', 'indigo',
            'violet', 'purple', 'fuchsia', 'pink', 'rose'
        ];

        $color = $colors[array_rand($colors)];

        return [
            'name' => $this->faker->unique()->word,
            'lightcolor' => $color . '-300',
            'darkcolor' => $color . '-900'
        ];
    }
}
