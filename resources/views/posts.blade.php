<x-layout>
    <div class="posts">
        @foreach ($posts as $post)
            <article class="post">
                <h1 class="medium-title">
                    <a href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                </h1>
                <div class="post-excerpt">
                    <p>{{ $post->excerpt . '...' }}</p>
                </div>
            </article>
        @endforeach
    </div>
</x-layout>
