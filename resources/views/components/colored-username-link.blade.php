@props(['size', 'user'])

@switch($size)
    @case('small')
        <a href="{{ route('profile.index', $user->username) }}"
            class="
        @if (!is_null($user->colour)) hover:text-black dark:hover:text-white
         text-{{ $user->colour->lightcolor }}
          dark:text-{{ $user->colour->darkcolor }} @endif hover:underline">
            {{ $user->username }}
        </a>
    @break

    @case('big')
        <div class="inline-block">
            <a href="{{ route('profile.index', $user->username) }}"
                class="hover:text-black dark:hover:text-white hover:underline dark:hover:decoration-white
        @if (!is_null($user->colour)) text-{{ $user->colour->lightcolor }} dark:text-{{ $user->colour->darkcolor }} @endif
        ">
                {{ $user->name }}</a>
            <a href="{{ route('profile.index', $user->username) }}" class="text-neutral-400">

                &#64;{{ $user->username }}
            </a>
        </div>
    @break

    @default
@endswitch
{{-- <a class="dark:text-neutral-400d --}}
