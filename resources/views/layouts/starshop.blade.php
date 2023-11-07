<x-main-layout>
    <div class="max-w-7xl m-auto bg-white p-6 dark:bg-neutral-800">
        <div
            class="flex justify-between items-center mb-6 pb-6 border-b border-neutral-300 dark:border-neutral-700 flex-col space-y-4 lg:space-y-0 lg:flex-row">
            <h1 class="large-title min-w-44">
                <a href="{{ route('starshop') }}">Star <x-star-icon class="text-yellow-600 dark:text-yellow-500" />
                    Shop</a>
            </h1>
            <nav>
                <ul class="flex space-x-4">
                    <x-starshop-nav-button :href="route('starshop')" :active="request()->routeIs('starshop')">All</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.wallpapers')" :active="request()->routeIs('starshop.wallpapers')">Wallpapers</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.profile-picture-frames')" :active="request()->routeIs('starshop.profile-picture-frames')">Profile Picture
                        Frames</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.post-frames')" :active="request()->routeIs('starshop.post-frames')">Post Frames</x-starshop-nav-button>
                    <x-starshop-nav-button :href="route('starshop.colours')" :active="request()->routeIs('starshop.colours')">Colours</x-starshop-nav-button>
                </ul>
            </nav>
            <span class="text-lg">Your stars: <x-price>{{ auth()->user()->stars }}</x-price></span>
        </div>
        <div class="w-full overflow-hidden p-1">
            {{ $slot }}
        </div>
    </div>
</x-main-layout>
