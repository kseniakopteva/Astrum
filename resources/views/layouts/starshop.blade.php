<x-main-layout>
    <div class="max-w-7xl m-auto bg-white p-6 dark:bg-neutral-800">
        <div class="flex justify-between items-center mb-6 pb-6 border-b border-neutral-300 dark:border-neutral-700">
            <h1 class="large-title w-44">
                <a href="{{ route('starshop') }}">Star <i class="fa-solid fa-star "></i> Shop</a>
            </h1>
            <nav>
                <ul class="flex space-x-4">
                    <x-starshop-nav-button :href="route('starshop')" :active="request()->routeIs('starshop')">All</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.wallpapers')" :active="request()->routeIs('starshop.wallpapers')">Wallpapers</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.profile-picture-frames')" :active="request()->routeIs('starshop.profile-picture-frames')">Profile Picture
                        Frames</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.post-frames')" :active="request()->routeIs('starshop.post-frames')">Post Frames</x-starshop-nav-button>
                </ul>
            </nav>
            <span>Your stars: {{ auth()->user()->stars }}<i class="fa-solid fa-star"></i></span>
        </div>
        <div class="w-full overflow-hidden">
            {{ $slot }}
        </div>
    </div>
</x-main-layout>
