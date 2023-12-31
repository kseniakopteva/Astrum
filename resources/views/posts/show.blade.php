<x-main-layout>
    <div class="wrapper">
        <x-panel :item="$post">
            <div class="lg:grid lg:grid-cols-4 gap-4 ">
                <div class="col-span-3 h-[calc(100vh-16rem)] w-full flex justify-center bg-neutral-200 dark:bg-neutral-900">
                    <img class=" h-full object-contain" src="<?php if (!strncmp('https', $post->image, 5)) {
                        echo $post->image;
                    } else {
                        echo asset('images/posts/' . $post->image);
                    } ?>" alt="">
                </div>
                <div class="lg:border-s border-neutral-300 dark:border-neutral-700 ps-4 flex flex-col">
                    <div class="flex justify-between items-baseline mt-4 lg:mt-0">

                        <x-colored-username-link size="big" :user="$post->author"></x-colored-username-link>

                        @if ((auth()->check() && auth()->user()->id == $post->author->id && $post->author->isBanned()) || (auth()->check() && !$post->author->isBanned()))
                            <x-dropdown align="right" width="52">
                                <x-slot name="trigger">
                                    <x-secondary-button type="submit" class="ml-2 !px-2 h-7 w-7"><i class="fa-solid fa-ellipsis"></i></x-secondary-button>
                                </x-slot>

                                <x-slot name="content">
                                    @if (auth()->check() && $post->author->id === auth()->user()->id)
                                        <form action="{{ route('post.delete') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $post->id }}">
                                            <button onclick="return confirm('Are you sure you want to delete this?')"
                                                class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                                Delete Post
                                            </button>
                                        </form>
                                    @elseif (auth()->check() && $post->author->id !== auth()->user()->id)
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'post-report')"
                                            class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
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

                        @if (!$post->tags->isEmpty())
                            <ul class="flex">
                                <x-tags :item="$post"></x-tags>
                            </ul>
                        @endif
                    </div>

                    <div class="flex justify-between items-center">
                        <p>{{ \Carbon\Carbon::parse($post->created_at)->format('D j F Y \a\t H:i') }}</p>
                        <x-likes route="post.like" :item="$post" :button="true"></x-likes>
                    </div>
                </div>
            </div>
        </div>
    </x-panel>

    <section class="space-y-4 max-w-3xl m-auto">

        @if (!$post->author->isBanned())
            @auth
                <x-comment-form route="post.comment.store" :item="$post" textarea_name="body"></x-comment-form>
            @else
                <p><a href="/register" class="underline">Register</a> or <a href="/login" class="underline">log in</a>
                    to
                    leave a comment!</p>
            @endauth
        @endif

        @foreach ($post->comments as $comment)
            @if (!$comment->removed)
                <x-comment :comment="$comment" />
            @endif
        @endforeach
    </section>

    <x-post-report-modal :post="$post" />
</div>
</x-main-layout>
