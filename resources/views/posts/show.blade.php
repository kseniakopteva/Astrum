<x-main-layout>
    <div class="wrapper">
        <article
            class="grid grid-cols-4 gap-8 border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4 mb-6">
            <div class="col-span-3 h-[calc(100vh-10rem)] w-full flex justify-center bg-neutral-950">
                <img class=" h-full object-contain" src="<?php if (!strncmp('https', $post->image, 5)) {
                    echo $post->image;
                } else {
                    echo asset('storage/images/posts/' . $post->image);
                } ?>" alt="">
            </div>
            <div class="">
                @auth
                    <div class="text-right">
                        <x-dropdown align="right" width="52">
                            <x-slot name="trigger">
                                <x-secondary-button type="submit" class="ml-2 !px-2 h-7 w-7"><i
                                        class="fa-solid fa-ellipsis"></i></x-secondary-button>
                            </x-slot>

                            <x-slot name="content">
                                @if (auth()->check() && $post->author->id === auth()->user()->id)
                                    <form action="{{ route('post.delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $post->id }}">
                                        <button onclick="return confirm('Are you sure you want to delete this?')"
                                            class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                            Delete Post
                                        </button>
                                    </form>
                                @elseif (auth()->check() && $post->author->id !== auth()->user()->id)
                                    <button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'post-report')"
                                        class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                        {{ __('Report') }}
                                    </button>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
                <div class="pt-12  flex flex-col">
                    <div>


                        <h1 class="large-title">
                            {{ $post->title }}
                        </h1>
                        <p><a
                                href="{{ route('profile.index', $post->author->username) }}">{{ $post->author->username }}</a>
                        </p>


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
                    <div class="flex justify-between">
                        <a href="{{ url()->previous() }}" class="inline-block p-2 mt-auto"><i
                                class="mr-1 fa-solid fa-arrow-left"></i>Go back</a>

                        <div class="flex space-x-1">
                            <span>{{ $post->likes->count() }}</span>
                            @if (auth()->check())
                                <form action="{{ route('post.like', $post->id) }}" method="POST">
                                    @csrf @method('POST')
                                    <button type="submit">
                                        @if ($post->isLiked($post))
                                            <i class="fa-solid fa-heart"></i>
                                        @else
                                            <i class="fa-regular fa-heart"></i>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <span>Likes</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </article>

        <section class="space-y-4 max-w-3xl m-auto">
            @auth
                <form action="/posts/{{ $post->slug }}/comments" method="post"
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 p-4 rounded-md">
                    @csrf
                    <header class="flex items-center">
                        <img src="{{ asset('storage/images/profile-pictures/' . auth()->user()->image) }}" alt=""
                            width="40" height="40" class="rounded-full">
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
                        <img src="{{ asset('storage/images/profile-pictures/' . $comment->author->image) }}"
                            alt="" width="60" height="60" class="rounded-full">
                    </div>
                    <div class="w-full">
                        <header class="mb-4">
                            <h3 class="font-bold"><a
                                    href="{{ route('profile.index', $comment->author->username) }}">{{ $comment->author->username }}</a>
                            </h3>
                            <p class="text-xs">
                                Posted on <time>{{ $comment->created_at->format('M j, Y, H:i') }}</time>
                            </p>
                        </header>
                        <p>{{ $comment->body }}</p>
                        <footer class="w-full flex justify-end">

                            <div class="flex space-x-1">
                                <span>{{ $comment->likes->count() }}</span>
                                @if (auth()->check())
                                    <form action="{{ route('postcomment.like', $comment->id) }}" method="POST">
                                        @csrf @method('POST')
                                        <button type="submit">
                                            @if ($comment->isLiked($comment))
                                                <i class="fa-solid fa-heart"></i>
                                            @else
                                                <i class="fa-regular fa-heart"></i>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span>Likes</span>
                                @endif
                            </div>
                        </footer>
                    </div>
                </article>
            @endforeach
        </section>

    </div>
    <x-post-report-modal :post="$post"></x-post-report-modal>
</x-main-layout>
