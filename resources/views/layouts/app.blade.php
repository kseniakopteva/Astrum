<x-main-layout>
    <div class="min-h-screen bg-neutral-100 dark:bg-neutral-900">

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-neutral-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</x-main-layout>
