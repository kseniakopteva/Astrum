<x-main-layout>

    @push('preloads')
        @foreach ($items as $item)
            @if ($item instanceof \App\Models\Post)
                <link rel="preload" href="{{ asset('images/posts/' . $item->image) }}" as="image">
            @endif
        @endforeach
    @endpush
    <div class="wrapper">
        <div class="flex justify-between items-center mb-4">
            <h2 class="medium-title">Feed</h2>
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
        <div id="masonry" class="masonry mx-auto">
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

        if (localStorage.getItem('feed_view') === null || localStorage.getItem("feed_view") === 'list') {

            gridIcon.style.display = 'none';
            listIcon.style.display = 'block';
            masonry.classList.remove('masonry');
            masonry.classList.add('max-w-xl');
            localStorage.setItem('feed_view', 'list');

        } else if (localStorage.getItem("feed_view") === 'grid') {

            gridIcon.style.display = 'block';
            listIcon.style.display = 'none';
            masonry.classList.add('masonry');
            masonry.classList.remove('max-w-xl');
            localStorage.setItem('feed_view', 'grid');

        };
    }

    function toggleView() {
        let gridIcon = document.getElementById('grid')
        let listIcon = document.getElementById('list')
        let masonry = document.getElementById('masonry')

        if (gridIcon.style.display === 'block') {
            localStorage.setItem('feed_view', 'list');
            window.location.reload();
        } else {
            localStorage.setItem('feed_view', 'grid');
            window.location.reload();
        }

    }
</script>
