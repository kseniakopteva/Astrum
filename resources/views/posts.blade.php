<x-layout>
    <div class="posts">
        @foreach ($posts as $post)
            <article class="post">
                <h1 class="medium-title title">
                    <a href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                </h1>
                <div class="post-excerpt">
                    <p>{{ $post->excerpt . '...' }}</p>
                </div>
                <footer class="post-footer">
                    <span><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </footer>
            </article>
        @endforeach
    </div>
</x-layout>
