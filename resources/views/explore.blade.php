<x-main-layout>

    @push('preloads')
        @if (isset($tag) && $tag)
            @foreach ($items as $item)
                @if ($item instanceof \App\Models\Post)
                    <link rel="preload" href="{{ asset('images/posts/' . $item->image) }}" as="image">
                @endif
            @endforeach
        @else
            @foreach ($posts as $post)
                <link rel="preload" href="{{ asset('images/posts/' . $post->image) }}" as="image">
            @endforeach
        @endif
    @endpush

    <div class="wrapper">
        @if (isset($tag) && $tag)
            <form class="main-search-form pt-3 pb-8" action="#" method="get">
            @else
                <form class="main-search-form pt-3 pb-8" action="{{ route('explore') }}" method="get">
        @endif
        <input
            class="input border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
            type="search" name="search" id="main-search" placeholder="Search for posts, notes and users..." value="{{ request('search') }}">

        <button class="icon-button" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        @if (request(['search']))
            <a href="{{ route('search', ['search' => request(['search'])['search']]) }}" class="float-right">Return to
                all
                posts</a>
        @endif

        </form>


        @if (isset($tag) && $tag)
            <div class="flex items-center mb-3 gap-3">
                <h2 class="medium-title">Posts and notes tagged <span class="px-2 py-0.5 bg-white/50 dark:bg-white/20 rounded-lg">{{ $tag->name }}</span>
                </h2>
                @if (request(['search']))
                    <span class="medium-title">searching "{{ request(['search'])['search'] }}"</span>
                @endif
            </div>
            <div class="masonry">
                @foreach ($items as $item)
                    @if ($item instanceof \App\Models\Post)
                        <x-feed-post :post="$item"></x-feed-post>
                    @elseif ($item instanceof \App\Models\Note)
                        <x-feed-note :note="$item"></x-feed-note>
                    @endif
                @endforeach
            </div>
        @else
            <div class="masonry">
                @foreach ($posts as $post)
                    <x-feed-post :post=$post></x-feed-post>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-main-layout>

<script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
<script>
    var macy_instance = Macy({
        container: '.masonry',
        trueOrder: false,
        waitForImages: true,
        debug: true,
        margin: 10,
        columns: 4,
        breakAt: {
            1024: 3,
            768: 2,
            640: 1
        }
    });

    macy_instance.runOnImageLoad(function() {
        macy_instance.recalculate(true);
    }, true);
</script>
