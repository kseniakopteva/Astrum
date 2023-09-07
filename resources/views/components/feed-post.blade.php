@props(['post'])

<article
    class="flex flex-col justify-between border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-6 pt-4">
    <div class="">
        <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <h1 class="medium-title mb-2">
                {{ $post->title }}
            </h1>
            <img class="w-full max-w-lg" src="/images/{{ $post->image }}" alt="{{ $post->alt }}">
        </a>
        @if ($post->body)
            <div>
                <p class="mt-4">{{ $post->excerpt . '...' }}</p>
            </div>
        @endif
    </div>
    <div class="">
        <footer class="flex items-center justify-between mt-4">
            <span><a class="dark:text-neutral-400"
                    href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </footer>
    </div>
</article>
