<x-profile-layout :user="$user" :posts="$posts" :followers="$followers" :following="$following">
    <div>
        <h2 class="medium-title mb-4 dark:text-white">Posts</h2>
        <div class="columns lg:columns-3 overflow-hidden mb-4">
            @foreach ($posts as $post)
                <x-feed-post class="cols-1" :post=$post></x-feed-post>
            @endforeach
        </div>
</x-profile-layout>
