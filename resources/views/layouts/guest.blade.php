<x-main-layout>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0  dark:bg-neutral-900 mt-8">

        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-neutral-500" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-neutral-800 shadow-md overflow-hidden sm:rounded-lg  border border-neutral-200">
            {{ $slot }}
        </div>
    </div>
</x-main-layout>
