<x-starshop-layout>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="medium-title">Profile Wallpapers</h2>
            <p>Stylish wallpapers for your profile that will make your posts shine!</p>
        </div>
        @if (auth()->check() &&
                auth()->user()->isCreator(auth()->user()))
            <a href="{{ route('starshop.wallpapers.create') }}"
                class="inline-flex items-center px-4 py-2 bg-lime-800 dark:bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-neutral-800 uppercase tracking-widest hover:bg-neutral-700 dark:hover:bg-white focus:bg-neutral-700 dark:focus:bg-white active:bg-neutral-900 dark:active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Submit
                Wallpaper</a>
        @endif
    </div>
    <div class="mt-4 grid grid-cols-3 gap-4">
        @foreach ($wallpapers as $wallpaper)
            <div class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-md">
                <img src="{{ asset('storage/images/wallpapers/' . $wallpaper->image) }}" alt="">
                <a href="">
                    <h3 class="small-title">{{ $wallpaper->name }}</h3>
                </a>
                <p class="my-2">{{ $wallpaper->description }}</p>
                <div class="flex justify-between items-end">
                    <span>Author: <a href="">{{ $wallpaper->author->username }}</a></span>
                    <span>{{ $wallpaper->price }}<i class="fa-solid fa-star"></i></span>
                </div>
            </div>
        @endforeach
    </div>
</x-starshop-layout>
