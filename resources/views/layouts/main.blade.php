@props(['user'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

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
    @vite (['resources/sass/main.scss'])

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/b3479de9e5.js" crossorigin="anonymous"></script>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @if (Request::segment(1) == 'profile')
        <?php $wallpaper = $user->currentWallpaper; ?> @if (isset($wallpaper) && !is_null($wallpaper))
            <style>
                body {

                    {{ 'background-image: linear-gradient(rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.3)), url(' . asset('storage/images/wallpapers/' . $wallpaper->image) . ');' }}
                }

                .dark body {

                    {{ 'background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' . asset('storage/images/wallpapers/' . $wallpaper->image) . ');' }}
                }
            </style>
        @endif
    @endif
</head>

<body class="flex flex-col min-h-screen bg-lime-900 dark:bg-neutral-900 text-neutral-600 dark:text-neutral-300">

    <header class="main-header">
        @include('layouts.navigation')
    </header>

    <main class="main-content flex-grow">
        {{ $slot }}
    </main>

    <footer
        class="h-24 bg-lime-950 dark:bg-neutral-900
        text-white dark:text-neutral-500 pt-2 px-4 flex items-center justify-between">
        <span>Copyright&copy; 2023, Ksenia Kopteva. All Rights Reserved</span>
        <button onclick="toggleTheme()" class="px-3 py-2 border border-neutral-300 rounded-md text-white space-x-2">
            <i class="fa-solid fa-moon" id="moon"></i>
            <i class="fa-solid fa-sun" id="sun"></i>
        </button>
    </footer>

    @if (session()->has('success'))
        <div class="flash-success
    fixed top-8 w-60 text-lg rounded-lg p-4 text-center bg-amber-100 border border-amber-300 shadow-md outline outline-2 outline-white
    dark:bg-amber-900 dark:border-amber-700 dark:outline-neutral-900 dark:text-white dark:shadow-neutral-700
    "
            x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <script>
        (addTheme = function() {

            /* ----------------------- this changes original theme ---------------------- */

            if (localStorage.getItem('theme') === null) {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.getElementsByTagName('html')[0].classList.add('dark')
                    document.querySelector('#sun').classList.add('opacity-20')
                    document.querySelector('#moon').classList.remove('opacity-20')
                }
                /* ----------------------------------- :) ----------------------------------- */
            } else if (localStorage.getItem("theme") === 'dark') {
                // make everything dark
                document.getElementsByTagName('html')[0].classList.add('dark')
                document.querySelector('#sun').classList.add('opacity-20')
                document.querySelector('#moon').classList.remove('opacity-20')
            } else {
                // make everything light
                document.getElementsByTagName('html')[0].classList.remove('dark')
                document.querySelector('#moon').classList.remove('opacity-20')
                document.querySelector('#sun').classList.add('opacity-20')
            }
        })()

        // function isDark() {
        //     if (localStorage.getItem("theme") === 'dark') {
        //         return true;
        //     }
        // }

        function toggleTheme() {
            html = document.getElementsByTagName('html')[0];
            if (html.classList.contains('dark')) {
                // make everything light
                html.classList.remove('dark')
                localStorage.setItem("theme", "light");
                document.querySelector('#sun').classList.remove('opacity-20')
                document.querySelector('#moon').classList.add('opacity-20')
            } else {
                // make everything dark
                html.classList.add('dark')
                localStorage.setItem("theme", "dark");
                document.querySelector('#sun').classList.add('opacity-20');
                document.querySelector('#moon').classList.remove('opacity-20')
            }

        }
    </script>
</body>

</html>
