@props(['post'])

<article class="flex flex-col justify-between border border-neutral-200 rounded-lg p-6 pt-4">
    <div class="">
        <a class="" href="/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <h1 class="medium-title mb-2">
                {{ $post->title }}
            </h1>
            <img class="w-full max-w-lg" src="/images/{{ $post->image }}" alt="{{ $post->alt }}">
        </a>
        <div>
            <p class="mt-4">{{ $post->excerpt . '...' }}</p>
        </div>
    </div>
    <div class="">
        <footer class="flex items-center justify-between mt-4">
            <span><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </footer>
    </div>
</article>
