<x-main-layout>

    {{-- Preloading images --}}

    @push('preloads')
        @if (
            (isset($tag) && $tag) ||
                (count(request(['search'])) !== 0 && !is_null(request(['search'])['search'])) ||
                (request(['sort']) && in_array(request(['sort'])['sort'], ['all', 'week', 'month', 'year'])))
            @foreach ($items as $item)
                @if ($item instanceof \App\Models\Post)
                    <link rel="preload" href="{{ asset('images/posts/' . $item->image) }}" as="image">
                @endif
            @endforeach
        @else
            @foreach ($items as $post)
                <link rel="preload" href="{{ asset('images/posts/' . $post->image) }}" as="image">
            @endforeach
        @endif
    @endpush

    <div class="wrapper">
        <div class="mb-8 mt-3 flex justify-between gap-10">

            <form class="main-search-form flex justify-between gap-10 flex-grow" action="#" method="get">
                <div class="relative flex-grow w-full">
                    <input
                        class="input border w-full border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
                        type="search" name="search" id="main-search" placeholder="Search for posts, notes and users..." value="{{ request('search') }}">

                    <button class="icon-button" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    @if (request(['search']) && !is_null(request(['search'])['search']))
                        <a href="{{ route('explore', ['search' => request(['search'])['search']]) }}" class="float-right">
                            Return to all posts</a>
                    @endif
                </div>

                {{-- Sort by time and type dropdown here --}}

                <select name="sort" id="exploreSort" onchange="this.form.submit()"
                    class="cursor-pointer border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <option value="all" @if (request(['sort']) && request(['sort'])['sort'] == 'all') selected="selected" @endif>All Recent Posts & Notes</option>
                    <option value="posts" @if (request(['sort']) && request(['sort'])['sort'] == 'posts') selected="selected" @endif>All Recent Posts</option>
                    <option value="notes" @if (request(['sort']) && request(['sort'])['sort'] == 'notes') selected="selected" @endif>All Recent Notes</option>
                    <option value="week" @if (request(['sort']) && request(['sort'])['sort'] == 'week') selected="selected" @endif>Best of this week</option>
                    <option value="month" @if (request(['sort']) && request(['sort'])['sort'] == 'month') selected="selected" @endif>Best of this month</option>
                    <option value="year" @if (request(['sort']) && request(['sort'])['sort'] == 'year') selected="selected" @endif>Best of this year</option>
                </select>
            </form>

            {{-- Mosaic / Feed --}}
            {{-- <svg xmlns="http://www.w3.org/2000/svg" height="3.25em"
                viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <style>
                    svg {
                        fill: #000000
                    }
                </style>
                <path
                    d="M128 136c0-22.1-17.9-40-40-40L40 96C17.9 96 0 113.9 0 136l0 48c0 22.1 17.9 40 40 40H88c22.1 0 40-17.9 40-40l0-48zm0 192c0-22.1-17.9-40-40-40H40c-22.1 0-40 17.9-40 40l0 48c0 22.1 17.9 40 40 40H88c22.1 0 40-17.9 40-40V328zm32-192v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V136c0-22.1-17.9-40-40-40l-48 0c-22.1 0-40 17.9-40 40zM288 328c0-22.1-17.9-40-40-40H200c-22.1 0-40 17.9-40 40l0 48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V328zm32-192v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V136c0-22.1-17.9-40-40-40l-48 0c-22.1 0-40 17.9-40 40zM448 328c0-22.1-17.9-40-40-40H360c-22.1 0-40 17.9-40 40v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V328z" />
            </svg> --}}

        </div>

        @if (count(array_filter(request(['search']))) !== 0)
            @if (!is_null(request(['search'])['search']) && !(isset($tag) && $tag))
                <h1 class="medium-title">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}"</strong></h1>
            @else
                <h1 class="medium-title">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}" in tag <span
                            class="px-2 py-0.5 bg-white/50 dark:bg-white/20 rounded-lg">{{ $tag->name }}</span></strong></h1>
            @endif
        @else
            @php
                if (request(['sort'])) {
                    switch (request(['sort'])['sort']) {
                        case 'all':
                            $heading = 'All Recent Posts & Notes';
                            break;
                        case 'posts':
                            $heading = 'All Recent Posts';
                            break;
                        case 'notes':
                            $heading = 'All Recent Notes';
                            break;
                        case 'week':
                            $heading = 'Best Posts & Notes this week';
                            break;
                        case 'month':
                            $heading = 'Best Posts & Notes this month';
                            break;
                        case 'year':
                            $heading = 'Best Posts & Notes this year';
                            break;

                        default:
                            $heading = 'All Recent Posts & Notes';
                            break;
                    }
                } else {
                    $heading = 'All Recent Posts & Notes';
                }
            @endphp

            <h1 class="medium-title mb-4">{{ $heading }}</h1>
        @endif

        @if (count(request(['search'])) === 0 || is_null(request(['search'])['search']))

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
                {{-- <div class="masonry">
                    @foreach ($posts as $post)
                        <x-feed-post :post=$post></x-feed-post>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $posts->links() }}
                </div> --}}
            @endif
        @else
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
                {{-- <div class="masonry">
            @foreach ($items as $item)
                @if ($item instanceof \App\Models\Post)
                    <x-feed-post :post="$item"></x-feed-post>
                @elseif ($item instanceof \App\Models\Note)
                    <x-feed-note :note="$item"></x-feed-note>
                @endif
            @endforeach
        </div> --}}
            @endif

            {{-- <p class="text-xl my-4 text-center font-bold ">To see more results try making your search more precise!</p> --}}

        @endif

        <div class="masonry">
            @foreach ($items as $item)
                @if ($item instanceof \App\Models\Post)
                    <x-feed-post :post="$item"></x-feed-post>
                @elseif ($item instanceof \App\Models\Note)
                    <x-feed-note :note="$item"></x-feed-note>
                @endif
            @endforeach
        </div>
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>

</x-main-layout>
