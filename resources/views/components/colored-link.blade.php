@props(['user', 'route'])

<a @if (!is_null($user->colour)) class="text-{{ $user->colour->lightcolor }} dark:text-{{ $user->colour->darkcolor }}" @endif
    href="{{ route($route, $user->username) }}">{{ $slot }}</a>
