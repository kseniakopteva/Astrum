@props(['slug', 'item'])


<div class="p-4 border border-neutral-200 dark:border-neutral-700 rounded-md">
    <a href="{{ route('starshop.' . $slug . '.show', $item->slug) }}">
        @if ($slug != 'post-frames')
            <img src="{{ asset('images/' . $slug . '/' . $item->image) }}" alt=""
                class="h-46 m-auto mb-4 w-auto hover:outline-dashed hover:outline-1 hover:outline-lime-500">
        @else
            <div class="w-full h-32 md:h-40 lg:h-48 mb-4 hover:outline-dashed hover:outline-1 hover:outline-lime-500"
                style="border-image: url('{{ asset('images/post-frames/' . $item->image) }}') {{ $item->percentage }}% round;
                    border-style: solid; border-width: {{ $item->width }}px !important;">
            </div>
        @endif
    </a>
    <a href="{{ route('starshop.' . $slug . '.show', $item->slug) }}">
        <h3 class="small-title">{{ $item->name }}</h3>
    </a>
    <p class="my-2">
        {{ strlen($item->description) <= 134 ? substr($item->description, 0, 134) : substr($item->description, 0, 134) . '...' }}
    </p>
    <div class="flex justify-between items-end">
        <span>Author: <x-colored-username-link size="small" :user="$item->author"></x-colored-username-link></span>
        <span><x-price>{{ $item->price }}</x-price></span>
    </div>
</div>
