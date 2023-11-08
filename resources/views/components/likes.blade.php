@props(['route', 'item', 'button' => false])

<div class="flex">
    <form action="{{ route($route, $item->id) }}" method="POST">
        @csrf @method('POST')

        @if ($item->author->isBanned($item->author))
            <x-secondary-button type="submit" class="space-x-0.5" disabled>
                <span>{{ $item->likes->count() }}</span>

                @if ($item->isLiked($item))
                    <i class="fa-solid text-red-600 dark:text-red-500
            fa-heart"></i>
                @else
                    <i class="fa-regular fa-heart"></i>
                @endif
            </x-secondary-button>
        @elseif ($button)
            <x-secondary-button type="submit" class="space-x-0.5">
                <span>{{ $item->likes->count() }}</span>

                @if ($item->isLiked($item))
                    <i class="fa-solid text-red-600 dark:text-red-500
                fa-heart"></i>
                @else
                    <i class="fa-regular fa-heart"></i>
                @endif
            </x-secondary-button>
        @else
            <button type="submit" class="space-x-0.5">

                <span>{{ $item->likes->count() }}</span>
                @if ($item->isLiked($item))
                    <i class="fa-solid text-red-600 dark:text-red-500
                    fa-heart"></i>
                @else
                    <i class="fa-regular fa-heart"></i>
                @endif
            </button>

        @endif
    </form>
</div>
