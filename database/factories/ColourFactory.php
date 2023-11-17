<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Colour>
 */
class ColourFactory extends Factory
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

        // $prices = array(3000, 3250, 3500, 700, 750, 800);

        $color = $colors[array_rand($colors)];

        return [
            'name' => $this->faker->unique()->word,
            'lightcolor' => $color . '-700',
            'darkcolor' => $color . '-300',
            'price' => 3000
        ];
    }
}
