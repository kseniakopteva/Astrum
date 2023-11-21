<div {{ $attributes->merge([
    'class' => 'bg-white dark:bg-neutral-800 bg-opacity-70 dark:bg-opacity-60 backdrop-blur-md
                max-w-7xl m-auto min-h-[calc(100vh-9rem)] p-6',
]) }}>
    {{ $slot }}
</div>
