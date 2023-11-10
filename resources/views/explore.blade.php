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
        <div class="masonry">
            @foreach ($posts as $post)
                <x-feed-post :post=$post></x-feed-post>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
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
