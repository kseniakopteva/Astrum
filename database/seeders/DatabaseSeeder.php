<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Tag::factory(10)->create();
        User::factory(10)->create()->each(function ($u) {

            Post::factory(2)->create([
                'user_id' => $u->id,
            ]);

            Note::factory(5)->create([
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
    }
}
