<x-main-layout>
    <div class="wrapper">
        <form class="
        main-search-form

         pt-3 pb-8" action="#" method="get">
            <input
                class="input
            border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md
            "
                type="search" name="search" id="main-search" placeholder="Search for posts..."
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
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</x-main-layout>
