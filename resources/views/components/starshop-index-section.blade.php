@props(['slug', 'name', 'array', 'cols'])

<section>
    <h3 class="small-title mt-6 mb-2"><a href="{{ route('starshop.wallpapers') }}">{{ $name }}</a></h3>
    <div class="grid grid-cols-<?php echo $cols; ?> gap-4">
        @foreach ($array as $item)
            <x-feed-starshop-item :slug="$slug" :item="$item"></x-feed-starshop-item>
        @endforeach
        <a href="{{ route('starshop.' . $slug) }}">
            <div
                class="w-full h-full grid place-content-center small-title underline text-neutral-400 hover:text-neutral-300 bg-black bg-opacity-30 rounded-md">
                See more...
            </div>
        </a>
    </div>
</section>
