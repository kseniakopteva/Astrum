@props(['post'])

<article id="{{ $post->slug }}"
    {{ $attributes->merge([
        'class' =>
            'first-letter:flex flex-col justify-between border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid',
    ]) }}
    @if (!is_null($post->post_frame)) style="border-image: url('{{ asset('storage/images/post-frames/' . $post->post_frame->image) }}') {{ $post->post_frame->percentage }}% round;
                    border-style: solid; border-width: {{ $post->post_frame->width }}px !important;" @endif>
    <div class="">
        <a class="" href="/u/{{ strtolower($post->author->username) }}/posts/{{ $post->slug }}">
            <div class="flex justify-between items-center">
                <h1 class="medium-title mr-2">
                    {{ $post->title }}
                </h1>
                <span>{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <h2 class="mb-2">
                <x-colored-username-link size="small" :user="$post->author"></x-colored-username-link>
            </h2>
            <img class="w-full" src="<?php if (!strncmp('https', $post->image, 5)) {
                echo $post->image;
            } else {
                echo asset('storage/images/posts/' . $post->image);
            } ?>" alt="{{ $post->alt }}">
        </a>
        @if ($post->body)
            <div>
                <p class="mt-4">{{ $post->excerpt . '...' }}</p>
            </div>
        @endif
    </div>
    <div class="">
        <footer class="mt-4 flex justify-between">
            <a href="{{ route('post.show', ['author' => $post->author, 'post' => $post]) }}"
                class="space-x-1"><span>{{ $post->comments->count() }}</span><i class="fa-regular fa-comment"></i></a>


            <x-likes route="post.like" :item="$post"></x-likes>
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
