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

<body class="flex flex-col min-h-screen">

    <header class="main-header">
        @include('layouts.navigation')
    </header>

    <main class="main-content flex-grow">
        {{ $slot }}
    </main>

    <footer class="h-36 bg-neutral-400 dark:bg-neutral-800 text-white pt-4 px-4 flex items-center justify-between">
        <span>Copyright bla-bla-bla</span>
        <span class="version">Version 0.0.1</span>
    </footer>

    @if (session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            <p>{{ session('success') }}</p>
        </div>
    @endif

</body>

</html>
