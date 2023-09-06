<x-main-layout>
    <div class="wrapper">
        <form class="main-search-form pt-3 pb-8" action="#" method="get">
            <input class="input"type="search" name="search" id="main-search" placeholder="Search for posts..."
                value="{{ request('search') }}">
            <button class="icon-button" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="grid sm:grid-cols-2 sm:gap-3 sm:px-3 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($posts as $post)
                <x-feed-post class="grid-item" :post=$post data-macy-complete="1"></x-feed-post>
            @endforeach
        </div>
        {{ $posts->links() }}
    </div>
</x-main-layout>
