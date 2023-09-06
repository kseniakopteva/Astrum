<nav x-data="{ open: false }"
    class="bg-white dark:bg-neutral-800 border-b border-neutral-100 dark:border-neutral-700 py-2 px-4 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-0">
        {{-- <div class="flex justify-between"> --}}

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center main-nav sm:justify-between">
            <a href="/" class="logo">Astrum</a>
            <ul class="space-x-2 flex list-none">
                <li><a href="/">Feed</a></li>
                <li><a href="/explore">Explore</a></li>
                <li><a href="/starshop">StarShop</a></li>
                <li><a href="/help">Help</a></li>
                @if (auth()->check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center text-neutral-500 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:text-neutral-700 dark:hover:text-neutral-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->username }}</div>

                                <x-arrow-svg class="h-4 w-4"></x-arrow-svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <li><a href="{{ route('login') }}">Log In</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 dark:text-neutral-500 hover:text-neutral-500 dark:hover:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-900 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-900 focus:text-neutral-500 dark:focus:text-neutral-400 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        {{-- </div> --}}
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                {{ __('Feed') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('explore')" :active="request()->routeIs('explore')">
                {{ __('Explore') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('starshop')" :active="request()->routeIs('starshop')">
                {{ __('StarShop') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('help')" :active="request()->routeIs('help')">
                {{ __('Help') }}
            </x-responsive-nav-link>
            {{-- @auth
                <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
            @endauth --}}
        </div>

        <!-- Responsive Settings Options -->
        @if (auth()->check())
            <div class="pt-4 pb-1 border-t border-neutral-200 dark:border-neutral-600">
                <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')">
                    <div>
                        <div class="font-medium text-base text-neutral-800 dark:text-neutral-200">
                            {{ Auth::user()->username }}
                        </div>
                        <div class="font-medium text-sm text-neutral-500">{{ Auth::user()->email }}</div>
                    </div>
                </x-responsive-nav-link>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-neutral-200 dark:border-neutral-600">

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endif
    </div>
</nav>
