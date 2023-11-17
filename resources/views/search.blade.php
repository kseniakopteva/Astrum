<x-main-layout>

    @push('preloads')
        @foreach ($items as $item)
            @if ($item instanceof \App\Models\Post)
                <link rel="preload" href="{{ asset('images/posts/' . $item->image) }}" as="image">
            @endif
        @endforeach
    @endpush

    <div class="wrapper mx-auto">
        <form class="main-search-form pt-3 pb-8" action="{{ route('search') }}" method="get">
            <input
                class="input border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
                type="search" name="search" id="main-search" placeholder="Search for posts..." value="{{ request('search') }}">
            <button class="icon-button" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <h1 class="medium-title">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}"</strong></h1>

        @if (count($users) !== 0)
            <h2 class="small-title mb-2 mt-3">Users</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach ($users as $user)
                    <div class="flex flex-col items-center border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid">
                        <a href="{{ route('profile.index', $user->username) }}">
                            <img src="{{ asset('storage/images/profile-pictures/' . $user->image) }}" alt="" class="rounded-full" width="130" height="130"></a>
                        <p class="text-lg mt-2">{{ $user->name }}</p>
                        <x-colored-username-link size="small" :user="$user">{{ $user->username }}</x-colored-username-link>
                    </div>
                @endforeach
            </div>
        @endif

        @if (count($items) !== 0)
            <h2 class="small-title mb-2 mt-3">Posts & Notes</h2>
            <div class="masonry">
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
