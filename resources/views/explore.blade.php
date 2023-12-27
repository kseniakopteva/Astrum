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
        <div class="mb-8 mt-3 flex justify-between items-start gap-10">
            <form class="main-search-form flex justify-between items-start gap-10 flex-grow" action="#" method="get">
                <div class="relative flex-grow w-full">
                    <input
                        class="input border w-full border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md"
                        type="search" name="search" id="main-search" placeholder="Search for posts, notes and users..." value="{{ request('search') }}">

                    <button class="icon-button" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    @if ((request(['search']) && !is_null(request(['search'])['search'])) || (request(['sort']) && request(['sort'])['sort'] !== 'all'))
                        @if (isset($tag) && $tag)
                            <a href="{{ url('/tags/' . $tag->slug . '?sort=all') }}" class="float-right">
                                Return to all '{{ $tag->slug }}' posts and notes</a>
                        @else
                            <a href="{{ route('explore', ['sort' => 'all']) }}" class="float-right">
                                Return to all posts & notes</a>
                        @endif
                    @elseif (isset($tag) && $tag)
                        <a href="{{ route('explore', ['sort' => 'all']) }}" class="float-right">
                            Return to all posts and notes</a>
                    @endif
                </div>

                <select name="sort" id="exploreSort" onchange="this.form.submit()"
                    class="cursor-pointer border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <option value="all" @if (request(['sort']) && request(['sort'])['sort'] == 'all') selected="selected" @endif>All Recent Posts & Notes</option>
                    <option value="posts" @if (request(['sort']) && request(['sort'])['sort'] == 'posts') selected="selected" @endif>All Recent Posts</option>
                    <option value="notes" @if (request(['sort']) && request(['sort'])['sort'] == 'notes') selected="selected" @endif>All Recent Notes</option>
                </select>
            </form>

            {{-- Mosaic / Feed --}}
            <button onclick="toggleView()">
                <svg xmlns="http://www.w3.org/2000/svg" height="3.25em" id="grid"
                    viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <style>
                        svg {
                            fill: #3F6212
                        }
                    </style>
                    <path
                        d="M128 136c0-22.1-17.9-40-40-40L40 96C17.9 96 0 113.9 0 136l0 48c0 22.1 17.9 40 40 40H88c22.1 0 40-17.9 40-40l0-48zm0 192c0-22.1-17.9-40-40-40H40c-22.1 0-40 17.9-40 40l0 48c0 22.1 17.9 40 40 40H88c22.1 0 40-17.9 40-40V328zm32-192v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V136c0-22.1-17.9-40-40-40l-48 0c-22.1 0-40 17.9-40 40zM288 328c0-22.1-17.9-40-40-40H200c-22.1 0-40 17.9-40 40l0 48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V328zm32-192v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V136c0-22.1-17.9-40-40-40l-48 0c-22.1 0-40 17.9-40 40zM448 328c0-22.1-17.9-40-40-40H360c-22.1 0-40 17.9-40 40v48c0 22.1 17.9 40 40 40h48c22.1 0 40-17.9 40-40V328z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" height="3.25em" id="list" style="display: none"
                    viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <style>
                        svg {
                            fill: #3F6212
                        }
                    </style>
                    <path
                        d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" />
                </svg>
            </button>
        </div>

        @if (count(array_filter(request(['search']))) !== 0)
            @if (!is_null(request(['search'])['search']) && !(isset($tag) && $tag))
                <h1 class="medium-title mb-4">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}"</strong></h1>
            @else
                <h1 class="medium-title mb-4">Search results for <strong class="font-bold">"{{ request(['search'])['search'] }}" in tag <span
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

                        default:
                            $heading = 'All Recent Posts & Notes';
                            break;
                    }
                } else {
                    $heading = 'All Recent Posts & Notes';
                }
            @endphp
            @if (!(isset($tag) && $tag))
                <h1 class="medium-title mb-4">{{ $heading }}</h1>
            @endif
        @endif

        @if (count(request(['search'])) === 0 || is_null(request(['search'])['search']))

            @if (isset($tag) && $tag)
                <div class="flex items-center mb-3 gap-3">
                    @php
                        if (request(['sort']) && request(['sort'])['sort'] == 'all') {
                            $title = 'Posts and notes';
                        } elseif (request(['sort']) && request(['sort'])['sort'] == 'posts') {
                            $title = 'Posts';
                        } elseif (request(['sort']) && request(['sort'])['sort'] == 'notes') {
                            $title = 'Notes';
                        } else {
                            $title = 'Posts and notes';
                        }
                    @endphp
                    <h1 class="medium-title mb-4">
                        {{ $title }} tagged
                        <span class="px-2 py-0.5 bg-white/50 dark:bg-white/20 rounded-lg">{{ $tag->name }}</span>
                    </h1>
                    @if (request(['search']))
                        <span class="medium-title">searching "{{ request(['search'])['search'] }}"</span>
                    @endif
                </div>
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

            @if (request(['sort']) && request(['sort'])['sort'] == 'all')
                <h2 class="small-title mb-2 mt-3">Posts & Notes</h2>
            @elseif (request(['sort']) && request(['sort'])['sort'] == 'posts')
                <h2 class="small-title mb-2 mt-3">Posts</h2>
            @elseif (request(['sort']) && request(['sort'])['sort'] == 'notes')
                <h2 class="small-title mb-2 mt-3">Notes</h2>
            @endif

        @endif

        <div class="masonry mx-auto" id="masonry">
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


<script>
    window.onload = function() {
        let gridIcon = document.getElementById('grid')
        let listIcon = document.getElementById('list')
        let masonry = document.getElementById('masonry')

        console.log(localStorage.getItem('explore_view'));

        if (localStorage.getItem('explore_view') === null || localStorage.getItem("explore_view") === 'grid') {

            gridIcon.style.display = 'block';
            listIcon.style.display = 'none';
            masonry.classList.add('masonry');
            masonry.classList.remove('max-w-xl');
            localStorage.setItem('explore_view', 'grid');

        } else if (localStorage.getItem("explore_view") === 'list') {

            gridIcon.style.display = 'none';
            listIcon.style.display = 'block';
            masonry.classList.add('max-w-xl');
            masonry.classList.remove('masonry');
            localStorage.setItem('explore_view', 'list');

        };
    }

    function toggleView() {
        let gridIcon = document.getElementById('grid')
        let listIcon = document.getElementById('list')
        let masonry = document.getElementById('masonry')

        if (gridIcon.style.display === 'block') {
            localStorage.setItem('explore_view', 'list');
            window.location.reload();
        } else {
            localStorage.setItem('explore_view', 'grid');
            window.location.reload();
        }

    }
</script>
