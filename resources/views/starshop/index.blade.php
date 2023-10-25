<x-starshop-layout>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="medium-title">Customize your profile!</h2>
            <p>Stylish wallpapers, profile picture frames and more - for your profile to shine!</p>
        </div>
    </div>
    <div>

        <?php
        $sections = [
            [
                'slug' => 'wallpapers',
                'name' => 'Wallpapers',
                'array' => $wallpapers,
                'cols' => 4,
            ],
            [
                'slug' => 'profile-picture-frames',
                'name' => 'Profile Picture Frames',
                'array' => $profile_picture_frames,
                'cols' => 6,
            ],
            // [
            //     'slug' => 'post-frames',
            //     'name' => 'Post Frames',
            //     'array' => $post_frames,
            //     'cols' => 6,
            // ],
        ];
        ?>

        @foreach ($sections as $section)
            <x-starshop-index-section :slug="$section['slug']" :name="$section['name']" :array="$section['array']"
                :cols="$section['cols']"></x-starshop-index-section>
        @endforeach


    </div>
</x-starshop-layout>
