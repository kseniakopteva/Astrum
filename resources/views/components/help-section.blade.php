<article {{ $attributes->merge(['class' => 'm-5']) }}>
    <x-panel class="p-6">
        <h2 class="text-2xl mb-3 dark:bg-neutral-700 border-b border-neutral-200 pb-3">
            {{ $heading }}
        </h2>
        {{ $slot }}
    </x-panel>
</article>
