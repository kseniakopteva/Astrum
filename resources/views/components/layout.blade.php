<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/css/reset.css">
    @vite (['resources/sass/main.scss', 'resources/sass/starshop.scss'])

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/b3479de9e5.js" crossorigin="anonymous"></script>

</head>

<body>

    <header class="main-header">
        <div class="border-bottom">
            <nav class="main-nav">
                <a href="/" class="logo">Astrum</a>
                <ul>
                    <li><a href="/">Feed</a></li>
                    <li><a href="/">Explore</a></li>
                    <li><a href="starshop">StarShop</a></li>
                    <li><a href="help">Help</a></li>
                    <li><a href="profile">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        {{ $slot }}
    </main>

    <footer class="main-footer">
        <span class="version">Version 0.0.1</span>
    </footer>

</body>

</html>
