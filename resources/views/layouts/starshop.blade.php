<x-main-layout>
    <x-page-panel class="!p-0">
        <div class="bg-gradient-to-r from-white dark:from-neutral-900 to-transparent flex justify-between items-center p-6 flex-col space-y-4 lg:space-y-0 lg:flex-row">
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
        <div class="overflow-hidden py-6 mx-6 border-t border-neutral-300 dark:border-neutral-700">
            {{ $slot }}
        </div>
    </x-page-panel>
</x-main-layout>
