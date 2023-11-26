@props(['item' => null, 'darker' => false, 'border' => true, 'accent' => false, 'user' => null, 'rounded' => true])

@php

    $class = 'flex-col justify-between p-4 mb-4 break-inside-avoid';

    if ($rounded) {
        $class .= ' rounded-lg';
    }

    if ($border) {
        $class .= ' border border-neutral-200 dark:border-neutral-700';
    }

    if ($darker) {
        $class .= ' bg-neutral-100 dark:bg-neutral-900 rounded-md';
    } else {
        $class .= ' bg-white dark:bg-neutral-800';
    }

    if ($accent) {
        $class .= ' border-l-4';
        if (!is_null($user->colour)) {
            $class .= ' border-' . $user->colour->lightcolor . ' dark:border-' . $user->colour->darkcolor;
        } else {
            $class .= ' border-lime-600 dark:border-lime-700 ';
        }
    }

@endphp


<article @if ($item instanceof \App\Models\Post) id="{{ $item->slug }}" @endif {{ $attributes->merge([
    'class' => $class,
]) }}
    @if ($item instanceof \App\Models\Post && !is_null($item->post_frame)) style="border-image: url('{{ asset('images/post-frames/' . $item->post_frame->image) }}') {{ $item->post_frame->percentage }}% round;
border-style: solid; border-width: {{ $item->post_frame->width }}px !important;" @endif>
    {{ $slot }}
</article>
