@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-med?ium text-sm text-neutral-700 dark:text-neutral-300']) }}>
    {{ $value ?? $slot }}
</label>
