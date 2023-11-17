<x-main-layout>

    @push('preloads')
        @foreach ($items as $item)
            @if ($item instanceof \App\Models\Post)
                <link rel="preload" href="{{ asset('images/posts/' . $item->image) }}" as="image">
            @endif
        @endforeach
    @endpush

    <div class="mt-6 max-w-xl m-auto">
        {{-- {{ dd($items) }} --}}
        @foreach ($items as $item)
            {{-- {{ dd(class_basename($item)) }} --}}
            @if ($item instanceof \App\Models\Post)
                <x-feed-post :post="$item"></x-feed-post>
                {{-- @elseif (class_basename($item) == 'note') --}}
            @elseif ($item instanceof \App\Models\Note)
                <x-feed-note :note="$item"></x-feed-note>
            @endif
        @endforeach
    </div>
</x-main-layout>
