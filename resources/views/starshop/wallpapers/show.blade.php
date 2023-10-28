<x-starshop-product-layout :item="$wallpaper" type='wallpaper'>
    <div class="border-b border-neutral-300 dark:border-neutral-700 pb-16">
        <img class="h-[calc(100vh-25rem)] m-auto shadow-lg rounded-lg border border-neutral-300 dark:border-neutral-900"
            src="{{ asset('storage/images/wallpapers/' . $wallpaper->image) }}" alt="">
    </div>
</x-starshop-product-layout>
