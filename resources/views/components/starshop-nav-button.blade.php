@props(['active'])

@php
    $classes = 'px-4 py-2  border border-neutral-200 dark:border-neutral-700 rounded-md inline-block';
    if ($active) {
        $classes .= ' bg-white dark:bg-neutral-800';
    } else {
        $classes .= ' bg-neutral-100 dark:bg-neutral-700';
    }
@endphp


<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
