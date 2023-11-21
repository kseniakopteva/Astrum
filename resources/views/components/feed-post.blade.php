@props(['post'])

<x-panel :item="$post">
    <div class="">
        <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <div class="flex justify-between items-center">
                <h1 class="medium-title mr-2">
                    {{ $post->title }}
                </h1>
                <span>{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </a>
        <h2 class="mb-2">
            <x-colored-username-link size="small" :user="$post->author"></x-colored-username-link>
        </h2>
        <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <img class="w-full" src="<?php if (!strncmp('https', $post->image, 5)) {
                echo $post->image;
            } else {
                echo asset('images/posts/' . $post->image);
            } ?>" alt="{{ $post->alt }}">
        </a>
        @if ($post->body)
            <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
                <p class="mt-4">{{ $post->excerpt . '...' }}</p>
            </a>
        @endif
    </div>
    <div class="">
        <footer class="mt-4 flex justify-between">
            <a href="{{ route('post.show', ['author' => $post->author, 'post' => $post]) }}" class="space-x-1"><span>{{ $post->comments->count() }}</span><i class="fa-regular fa-comment"></i></a>


            <x-likes route="post.like" :item="$post"></x-likes>
        </footer>
    </div>
</x-panel>

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
