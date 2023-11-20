<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AboutLink;
use App\Models\Badge;
use App\Models\Colour;
use App\Models\FAQuestion;
use App\Models\PostComment;
use App\Models\Note;
use App\Models\Post;
use App\Models\PostFrame;
use App\Models\Product;
use App\Models\ProfilePictureFrame;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wallpaper;
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

        /* -------------------------------------------------------------------------- */
        /*                                   BADGES                                   */
        /* -------------------------------------------------------------------------- */

        $badges = [
            ['name' => 'drawer', 'lightcolor' => 'lime-300', 'darkcolor' => 'lime-900'],
            ['name' => 'writer', 'lightcolor' => 'teal-300', 'darkcolor' => 'teal-900'],
            ['name' => 'crafter', 'lightcolor' => 'amber-300', 'darkcolor' => 'amber-900'],
            ['name' => 'painter', 'lightcolor' => 'rose-300', 'darkcolor' => 'rose-900'],
            ['name' => 'poet', 'lightcolor' => 'indigo-300', 'darkcolor' => 'indigo-900'],
            ['name' => 'illustrator', 'lightcolor' => 'green-300', 'darkcolor' => 'green-900'],
            ['name' => 'cartoonist', 'lightcolor' => 'orange-300', 'darkcolor' => 'orange-900'],
        ];
        foreach ($badges as $badge) {
            Badge::factory()->create($badge);
        }

        /* -------------------------------------------------------------------------- */
        /*                                    TAGS                                    */
        /* -------------------------------------------------------------------------- */

        $tags = ['art', 'aesthetic', 'photo', 'beautiful', 'new post', 'gorgeous', 'love', 'painting'];
        foreach ($tags as $tag) {
            Tag::factory()->create(['name' => $tag, 'slug' => str_replace(" ", "-", $tag)]);
        }

        /* -------------------------------------------------------------------------- */
        /*                  USERS, POSTS, NOTES, FAQ, PRODUCTS, ABOUT                 */
        /* -------------------------------------------------------------------------- */

        $GLOBALS['counter'] = 1;
        User::factory(10)->create(['role' => 'creator'])->each(function ($u) {

            for ($i = 0; $i < 6; $i++) {
                Post::factory()->create([
                    'user_id' => $u->id,
                    'image' => $GLOBALS['counter'] . '.jpg'
                ]);
                $GLOBALS['counter']++;
            }

            Note::factory(6)->create([
                'user_id' => $u->id,
            ]);

            FAQuestion::factory(5)->create([
                'user_id' => $u->id,
            ]);

            $products = [
                [
                    'image' => '1.jpg',
                    'name' => 'Sketch Commission',
                    'slug' => 'sketch-commission_' . $u->id,
                    'type' => 'unlimited',
                    'max_slots' => 15,
                    'price' => 25
                ],
                [
                    'image' => '2.jpg',
                    'name' => 'Lineart Commission',
                    'slug' => 'lineart-commission_' . $u->id,
                    'type' => 'unlimited',
                    'max_slots' => 10,
                    'price' => 40
                ],
                [
                    'image' => '3.jpg',
                    'name' => 'Painting Commission',
                    'slug' => 'painting-commission_' . $u->id,
                    'type' => 'unlimited',
                    'max_slots' => 5,
                    'price' => 80
                ],
            ];

            foreach ($products as $product) {

                Product::factory()->create([
                    'user_id' => $u->id,
                    'image' => $product['image'],
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'type' => $product['type'],
                    'max_slots' => $product['max_slots'],
                    'price' => $product['price'],
                ]);
            }

            $links = ['ArtStation', 'Patreon', 'My Personal Website'];

            for ($i = 0; $i < 3; $i++) {
                AboutLink::factory()->create([
                    'user_id' => $u->id,
                    'name' => $links[$i],
                    'link' => '#'
                ]);
            }
        });

        $posts = Post::all();

        // Populate the pivot table
        Tag::all()->each(function ($tag) use ($posts) {
            $tag->posts()->attach(
                $posts->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        for ($i = 1; $i <= 60; $i++) {
            PostComment::factory(3)->create([
                'user_id' => rand(1, 10),
                'post_id' => $i
            ]);
        }

        User::factory()->create([
            'role' => 'mod',
            'username' => 'ksenia',
            'name' => 'Ksenia K',
            'password' => Hash::make('12345678'),
            'stars' => 1000000
        ]);

        /* -------------------------------------------------------------------------- */
        /*                                   COLOURS                                  */
        /* -------------------------------------------------------------------------- */

        $colours = [
            ['name' => 'Pink', 'lightcolor' => 'pink-700', 'darkcolor' => 'pink-300'],
            ['name' => 'Red', 'lightcolor' => 'red-700', 'darkcolor' => 'red-300'],
            ['name' => 'Orange', 'lightcolor' => 'orange-700', 'darkcolor' => 'orange-300'],
            ['name' => 'Yellow', 'lightcolor' => 'yellow-700', 'darkcolor' => 'yellow-300'],
            ['name' => 'Green', 'lightcolor' => 'green-700', 'darkcolor' => 'green-300'],
            ['name' => 'Teal', 'lightcolor' => 'teal-700', 'darkcolor' => 'teal-300'],
            ['name' => 'Blue', 'lightcolor' => 'sky-700', 'darkcolor' => 'sky-300'],
            ['name' => 'Indigo', 'lightcolor' => 'indigo-700', 'darkcolor' => 'indigo-300'],
            ['name' => 'Purple', 'lightcolor' => 'purple-700', 'darkcolor' => 'purple-300'],
        ];

        foreach ($colours as $colour) {
            Colour::factory()->create([
                'name' => $colour['name'],
                'lightcolor' => $colour['lightcolor'],
                'darkcolor' => $colour['darkcolor'],
            ]);
        }

        /* -------------------------------------------------------------------------- */
        /*                                 WALLPAPERS                                 */
        /* -------------------------------------------------------------------------- */

        for ($i = 1; $i <= 5; $i++) {
            Wallpaper::factory()->create(['image' => $i . '.jpg']);
        }
        $wallpapers = Wallpaper::all();
        // Populate the pivot table
        Tag::all()->each(function ($tag) use ($wallpapers) {
            $tag->wallpapers()->attach(
                $wallpapers->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        /* -------------------------------------------------------------------------- */
        /*                           PROFILE PICTURE FRAMES                           */
        /* -------------------------------------------------------------------------- */

        for ($i = 1; $i <= 8; $i++) {
            ProfilePictureFrame::factory()->create(['image' => $i . '.png']);
        }
        $ppf = ProfilePictureFrame::all();
        // Populate the pivot table
        Tag::all()->each(function ($tag) use ($ppf) {
            $tag->profile_picture_frames()->attach(
                $ppf->random(rand(1, 5))->pluck('id')->toArray()
            );
        });


        /* -------------------------------------------------------------------------- */
        /*                                 POST FRAMES                                */
        /* -------------------------------------------------------------------------- */

        $pf = [
            ['image' => '1.png', 'width' => 25, 'percentage' => 25],
            ['image' => '2.png', 'width' => 30, 'percentage' => 25],
            ['image' => '3.png', 'width' => 30, 'percentage' => 25],
            ['image' => '4.png', 'width' => 10, 'percentage' => 12.5],
            ['image' => '5.png', 'width' => 60, 'percentage' => 25],
        ];
        foreach ($pf as $frame) {
            PostFrame::factory()->create([
                'image' => $frame['image'],
                'width' => $frame['width'],
                'percentage' => $frame['percentage'],
            ]);
        }
        $pf = PostFrame::all();
        // Populate the pivot table
        Tag::all()->each(function ($tag) use ($pf) {
            $tag->post_frames()->attach(
                $pf->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}
