@props(['slug', 'name', 'array', 'cols'])

<section class="">
    <h3 class="small-title mt-6 mb-2"><a href="{{ route('starshop.wallpapers') }}">{{ $name }}</a></h3>
    <div class="grid grid-cols-{{ $cols - 3 }} sm:grid-cols-{{ $cols - 2 }} md:grid-cols-{{ $cols - 1 }} lg:grid-cols-{{ $cols }} gap-4">
        @foreach ($array as $item)
            <x-feed-starshop-item :slug="$slug" :item="$item"></x-feed-starshop-item>
        @endforeach
        <a href="{{ route('starshop.' . $slug) }}" class="p-5">
            <div
                class="w-full h-full grid place-content-center small-title underline rounded-xl
                 text-neutral-500 dark:text-neutral-400 hover:text-neutral-400 hover:bg-neutral-200 bg-neutral-300 dark:bg-neutral-900 dark:hover:bg-neutral-900/50 dark:hover:text-netral-300">
                See more...
            </div>
        </a>
    </div>
</section>
