<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Badge;
use App\Models\FAQuestion;
use App\Models\PostComment;
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
    }
}
