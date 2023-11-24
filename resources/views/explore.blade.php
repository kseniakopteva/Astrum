<x-main-layout>

    @push('preloads')
        @if ((isset($tag) && $tag) || (count(request(['search'])) !== 0 && !is_null(request(['search'])['search'])))
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

    @if (count(request(['search'])) === 0 || is_null(request(['search'])['search']))
        <div class="wrapper">
            <div class="border border-red-400 mb-8 mt-3 flex justify-between">
                <form class="main-search-form" action="#" method="get">
                    <input
                        class="input border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
                        type="search" name="search" id="main-search" placeholder="Search for posts, notes and users..." value="{{ request('search') }}">

                    <button class="icon-button" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    {{-- @if (request(['search']) && !is_null(request(['search'])['search']))
                        <a href="{{ route('explore', ['search' => request(['search'])['search']]) }}" class="float-right">
                            Return to all posts</a>
                    @endif --}}
                </form>

                {{-- Order by time dropdown here --}}
                <form action="">
                    <select name="" id="">
                        <option value="all">All Recent Posts & Notes</option>
                        <option value="posts">All Recent Posts</option>
                        <option value="notes">All Recent Notes</option>
                        <option value="week">Best of this week</option>
                        <option value="month">Best of this month</option>
                        <option value="year">Best of this year</option>
                    </select>
                </form>

                {{-- Mosaic / Feed --}}

            </div>

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
    @else
        <div class="wrapper mx-auto">
            <form class="main-search-form pt-3 pb-8" action="{{ route('explore') }}" method="get">
                <input
                    class="input border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
                    type="search" name="search" id="main-search" placeholder="Search for posts..." value="{{ request('search') }}">
                <button class="icon-button" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                @if (request(['search']) && !is_null(request(['search'])['search']))
                    <a href="{{ route('explore', ['search' => request(['search'])['search']]) }}" class="float-right">
                        Return to all posts</a>
                @endif
            </form>
            @if (!(isset($tag) && $tag))
                <h1 class="medium-title">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}"</strong></h1>
            @else
                <h1 class="medium-title">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}" in tag <span
                            class="px-2 py-0.5 bg-white/50 dark:bg-white/20 rounded-lg">{{ $tag->name }}</span></strong></h1>
            @endif

            @if (!(isset($tag) && $tag) && !is_null($users) && count($users) !== 0)
                <h2 class="small-title mb-2 mt-3">Users</h2>
                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                    @foreach ($users as $user)
                        <div class="flex flex-col items-center border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 break-inside-avoid">
                            <a href="{{ route('profile.index', $user->username) }}">
                                <img src="{{ asset('storage/images/profile-pictures/' . $user->image) }}" alt="" class="rounded-full" width="130" height="130"></a>
                            <p class="text-lg mt-2">{{ $user->name }}</p>
                            <x-colored-username-link size="small" :user="$user">{{ $user->username }}</x-colored-username-link>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (!is_null($items) && count($items) !== 0)
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
    @endif

</x-main-layout>
