<x-main-layout>
    <form class="main-search-form pt-3 pb-8" action="#" method="get">
        <input class="input"type="search" name="search" id="main-search" placeholder="Search for posts..."
            value="{{ request('search') }}">
        <button class="icon-button" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
    <div class="grid sm:grid-cols-2 sm:gap-3 sm:px-3 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($posts as $post)
            <article class="post">
                <a href="/posts/{{ $post->slug }}">
                    <h1 class="medium-title title">
                        {{ $post->title }}
                    </h1>
                    <img src="/images/{{ $post->image }}" alt="">
                    <div class="post-excerpt">
                        <p>{{ $post->excerpt . '...' }}</p>
                    </div>
                </a>
                <footer class="flex items-center justify-between mt-4">
                    <span><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </footer>
            </article>
        @endforeach
    </div>
    {{ $posts->links() }}
</x-main-layout>
