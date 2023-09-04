<x-layout>
    <article class="post">
        <h1 class="large-title">
            <a href="/post">{{ $post->title }}</a>
        </h1>
        <p>by <a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></p>
        <img src="https://placehold.co/900x400" alt="">
        <div class="post-content">
            <p>{{ $post->body }}</p>
        </div>
        <ul class="tags">
            @foreach ($post->tags as $tag)
                <li><a href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a></li>
            @endforeach
        </ul>
        <a href="/" class="go-back-link"><i class="fa-solid fa-arrow-left"></i>Go back</a>
    </article>
</x-layout>
