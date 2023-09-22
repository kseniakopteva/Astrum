<x-main-layout>
    <div class="wrapper">
        <article
            class="grid grid-cols-4 gap-8 border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4 mb-6">
            <div class="col-span-3 h-[calc(100vh-10rem)] w-full flex justify-center bg-neutral-950">
                <img class=" h-full object-contain" src="<?php if (!strncmp('https', $post->image, 5)) {
                    echo '';
                } else {
                    echo '/images/';
                } ?>{{ $post->image }}" alt="">
            </div>
            <div class="">
                <div class="text-right">
                    @if ($post->author->id === auth()->user()->id)
                        <form action="{{ route('post.delete', $post) }}" method="POST">
                            {{-- @method('delete') --}}
                            @csrf
                            <input type="hidden" name="id" value="{{ $post->id }}">
                            <x-danger-button href="/post/delete">Delete Post</x-danger-button>
                        </form>
                    @endif
                </div>
                <div class="pt-12  flex flex-col">
                    <div>


                        <h1 class="large-title">
                            {{ $post->title }}
                        </h1>
                        <p><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></p>


                        <div class="text-lg my-8">
                            <p>{{ $post->body }}</p>
                        </div>
                        <ul class="flex">
                            @foreach ($post->tags as $tag)
                                <li class="mr-1"><a
                                        class="px-2 py-0.5 rounded-md bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-400 inline-block"
                                        href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ url()->previous() }}" class="inline-block p-2 mt-auto"><i
                            class="mr-1 fa-solid fa-arrow-left"></i>Go back</a>
                </div>
            </div>
        </article>

        <section class="space-y-4 max-w-3xl m-auto">
            @auth
                <form action="/posts/{{ $post->slug }}/comments" method="post"
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 p-4 rounded-md">
                    @csrf
                    <header class="flex items-center">
                        <img src="/images/{{ auth()->user()->image }}" alt="" width="40" height="40"
                            class="rounded-full">
                        <h2 class="ml-4">Join the discussion...</h2>
                    </header>
                    <div class="mt-6">
                        <x-textarea class="w-full" name="body" rows="5"
                            placeholder="Write something polite and constructive here..." required></x-textarea>
                        <x-input-error :messages="$errors->get('body')"></x-input-error>
                    </div>
                    <div class="flex justify-end"><x-primary-button>Post</x-primary-button></div>
                </form>
            @else
                <p><a href="/register" class="underline">Register</a> or <a href="/login" class="underline">log in</a> to
                    leave a comment!</p>
            @endauth
            @foreach ($post->comments as $comment)
                <article
                    class="flex max-w-3xl m-auto bg-neutral-100 border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 p-4 rounded-md
            space-x-4">
                    <div class="flex-shrink-0">
                        <img src="/images/{{ $comment->author->image }}" alt="" width="60" height="60"
                            class="rounded-full">
                    </div>
                    <div>
                        <header class="mb-4">
                            <h3 class="font-bold"><a
                                    href="/u/{{ $comment->author->username }}">{{ $comment->author->username }}</a>
                            </h3>
                            <p class="text-xs">
                                Posted on <time>{{ $comment->created_at->format('M j, Y, H:i') }}</time>
                            </p>
                        </header>
                        <p>{{ $comment->body }}</p>
                    </div>
                </article>
            @endforeach
        </section>

    </div>
</x-main-layout>
