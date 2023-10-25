<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Badge;
use App\Models\Colour;
use App\Models\FAQuestion;
use App\Models\PostComment;
use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Badge::factory()->create(['name' => 'drawer']);
        Badge::factory()->create(['name' => 'writer']);
        Badge::factory()->create(['name' => 'crafter']);
        Badge::factory()->create(['name' => 'painter']);
        Badge::factory()->create(['name' => 'poet']);
        Badge::factory()->create(['name' => 'illustrator']);
        Badge::factory()->create(['name' => 'cartoonist']);

        Tag::factory(10)->create();
        User::factory(20)->create(['role' => 'creator'])->each(function ($u) {

            Post::factory(10)->create([
                'user_id' => $u->id,
            ]);

            Note::factory(15)->create([
                'user_id' => $u->id,
            ]);

            FAQuestion::factory(5)->create([
                'user_id' => $u->id,
            ]);
        });

        $posts = Post::all();

        // Populate the pivot table
        Tag::all()->each(function ($tag) use ($posts) {
            $tag->posts()->attach(
                $posts->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        for ($i = 1; $i <= 20; $i++) {
            PostComment::factory(5)->create([
                'user_id' => rand(1, 10),
                'post_id' => $i
            ]);
        }

        User::factory()->create([
            'role' => 'creator',
            'username' => 'ksenia',
            'name' => 'Ksenia K',
            'password' => Hash::make('12345678')
        ]);


        $colours = [
            [
                'name' => 'Cute Purple',
                'hex' => 'E0BBE4',
                'price' => 15
            ],
            [
                'name' => 'Pastel Pink',
                'hex' => 'D291BC',
                'price' => 15
            ],
            [
                'name' => 'Blush Red',
                'hex' => 'FD8A8A',
                'price' => 15
            ],
            [
                'name' => 'Beige Pink',
                'hex' => 'FFCBCB',
                'price' => 15
            ],
            [
                'name' => 'Cyan Lagoon',
                'hex' => 'A8D1D1',
                'price' => 15
            ],
            [
                'name' => 'Yellow Lemon',
                'hex' => 'F1F7B5',
                'price' => 15
            ]
        ];

        foreach ($colours as $colour) {
            Colour::factory()->create([
                'name' => $colour['name'],
                'hex' => $colour['hex'],
                'price' => $colour['price'],
            ]);
        }
    }
}
