<x-starshop-layout>
    <div class="flex items-center justify-between">
        <div class="mr-8">
            <h2 class="medium-title">Profile Wallpapers</h2>
            <p>Stylish wallpapers for your profile that will make your posts shine!</p>
        </div>
        @if (auth()->check() &&
                auth()->user()->isCreatorOrMore(auth()->user()))
            <a href="{{ route('starshop.wallpapers.create') }}"
                class="hover:text-white inline-flex items-center px-4 py-2 bg-lime-800 dark:bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-neutral-800 uppercase tracking-widest hover:bg-lime-700 dark:hover:bg-white focus:bg-lime-700 dark:focus:bg-white active:bg-lime-900 dark:active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Submit
                Wallpaper</a>
        @endif
    </div>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($wallpapers as $wallpaper)
            <x-feed-starshop-item slug="wallpapers" :item="$wallpaper"></x-feed-starshop-item>
        @endforeach
    </div>
</x-starshop-layout>
