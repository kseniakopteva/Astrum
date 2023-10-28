<x-main-layout>
    <div class="wrapper">
        <article
            class="lg:grid lg:grid-cols-4 gap-4 border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm px-4 py-6 mb-6">
            <div class="col-span-3 h-[calc(100vh-16rem)] w-full flex justify-center bg-neutral-200 dark:bg-neutral-900">
                <img class=" h-full object-contain" src="<?php if (!strncmp('https', $post->image, 5)) {
                    echo $post->image;
                } else {
                    echo asset('storage/images/posts/' . $post->image);
                } ?>" alt="">
            </div>
            <div class="lg:border-s border-neutral-300 dark:border-neutral-700 ps-4 flex flex-col">
                <div class="flex justify-between items-baseline mt-4 lg:mt-0">

                    <x-colored-username-link size="big" :user="$post->author"></x-colored-username-link>

                    @auth
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
                    @endauth
                </div>
                <div class=" flex flex-col justify-between flex-grow">
                    <div>

                        <h1 class="large-title pt-4">
                            {{ $post->title }}
                        </h1>

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

                    <div class="self-end justify-self-end">
                        {{-- <a href="{{ url()->previous() }}" class="inline-block p-2 mt-auto"><i
                                class="mr-1 fa-solid fa-arrow-left"></i>Go back</a> --}}

                        <x-likes route="post.like" :item="$post" :button="true"></x-likes>

                    </div>
                </div>
            </div>
        </article>

        <section class="space-y-4 max-w-3xl m-auto">
            @auth
                <x-comment-form route="post.comment.store" :item="$post" textarea_name="body"></x-comment-form>
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
                            <h3 class="font-bold"><x-colored-username-link size="small"
                                    :user="$comment->author"></x-colored-username-link>
                            </h3>
                            <p class="text-xs">
                                Posted on <time>{{ $comment->created_at->format('M j, Y, H:i') }}</time>
                            </p>
                        </header>
                        <p>{{ $comment->body }}</p>
                        <footer class="w-full flex justify-end">

                            <x-likes route="postcomment.like" :item="$comment"></x-likes>
                        </footer>
                    </div>
                </article>
            @endforeach
        </section>

    </div>
    <x-post-report-modal :post="$post"></x-post-report-modal>
</x-main-layout>
