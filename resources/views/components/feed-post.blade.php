@props(['post'])

<article
    class="flex flex-col justify-between border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-6 pt-4 mb-4 break-inside-avoid">
    <div class="">
        <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <h1 class="medium-title mb-2">
                {{ $post->title }}
            </h1>
            <img class="w-full max-w-lg" src="<?php if (!strncmp('https', $post->image, 5)) {
                echo '';
            } else {
                echo '/images/';
            } ?>{{ $post->image }}" alt="{{ $post->alt }}">
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


{{-- <div
    class="relative mb-4 before:content-[''] before:rounded-md before:absolute before:inset-0 before:bg-black before:bg-opacity-20">
    <img class="w-full rounded-md" src="https://source.unsplash.com/random/<?php echo rand(1, 20); ?>"> --}}
{{-- <div class="test__body absolute inset-0 p-8 text-white flex flex-col">
        <div class="relative">
            <a class="test__link absolute inset-0" target="_blank" href="/"></a>
            <h1 class="test__title text-3xl font-bold mb-3">Title post1</h1>
            <p class="test__author font-sm font-light">Author</p>
        </div>
        <div class="mt-auto">
            <span class="test__tag bg-white bg-opacity-60 py-1 px-4 rounded-md text-black">#tag</span>
        </div>
    </div> --}}
{{-- </div> --}}
