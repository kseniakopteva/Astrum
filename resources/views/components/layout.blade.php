<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Astrum') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/css/reset.css">
    @vite (['resources/sass/main.scss', 'resources/sass/starshop.scss', 'resources/sass/profile.scss'])

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/b3479de9e5.js" crossorigin="anonymous"></script>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body>

    <header class="main-header">
        {{-- <nav class="main-nav">
            <a href="/" class="logo">Astrum</a>
            <ul>
                <li><a href="/">Feed</a></li>
                <li><a href="/explore">Explore</a></li>
                <li><a href="/starshop">StarShop</a></li>
                <li><a href="/help">Help</a></li>

                @if (Route::has('login'))
                    @auth
                        <li><a href="{{ url('/profile') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Profile</a>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                                in</a></li>

                        @if (Route::has('register'))
                            <li> <a href="{{ route('register') }}"
                                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                            </li>
                        @endif
                    @endauth
                @endif

            </ul>
        </nav> --}}

        @include('layouts.navigation')
    </header>

    <main class="main-content">
        {{ $slot }}
    </main>

    <footer class="main-footer">
        <span class="version">Version 0.0.1</span>
    </footer>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition class="flash-success">
            <p>{{ session('success') }}</p>
        </div>
    @endif

</body>

</html>
