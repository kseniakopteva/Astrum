<x-layout>
    <form class="main-search-form" action="#" method="get">
        <input class="input"type="search" name="search" id="main-search" placeholder="Search for posts, users or tags..."
            value="{{ request('search') }}">
        <button class="icon-button" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
    <div class="posts">
        @foreach ($posts as $post)
            {{-- <a href="/posts/{{ $post->slug }}"> --}}
            <article class="post">
                <h1 class="medium-title title">
                    {{ $post->title }}
                </h1>
                <img src="https://placehold.co/600x400" alt="">
                <div class="post-excerpt">
                    <p>{{ $post->excerpt . '...' }}</p>
                </div>
                <footer class="post-footer">
                    <span><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </footer>
            </article>
            {{-- </a> --}}
        @endforeach
    </div>
    {{ $posts->links() }}
</x-layout>
