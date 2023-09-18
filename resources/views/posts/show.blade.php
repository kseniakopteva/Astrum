<x-main-layout>
    <div class="wrapper">
        <article
            class="border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4 mb-8">
            <h1 class="large-title">
                {{ $post->title }}
            </h1>
            <p><a href="/u/{{ $post->author->username }}">{{ $post->author->username }}</a></p>
            <img class="max-w-lg my-4" src="/images/{{ $post->image }}" alt="">
            <div class="text-lg">
                <p>{{ $post->body }}</p>
            </div>
            <ul class="flex">
                @foreach ($post->tags as $tag)
                    <li class="mr-1"><a
                            class="px-2 py-0.5 rounded-md bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-400 inline-block"
                            href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a></li>
                @endforeach
            </ul>
            <a href="{{ url()->previous() }}" class="inline-block mt-4 p-2"><i
                    class="mr-1 fa-solid fa-arrow-left"></i>Go back</a>
        </article>

        <section class="space-y-4 max-w-3xl m-auto">
            @auth
                <form action="/posts/{{ $post->slug }}/comments" method="post"
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 p-4 rounded-md">
                    @csrf
                    <header class="flex items-center">
                        <img src="https://i.pravatar.cc/60?u={{ auth()->id() }}" alt="" width="40"
                            height="40" class="rounded-full">
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
                        <img src="https://i.pravatar.cc/60?u={{ $comment->author->id }}" alt="" width="60"
                            height="60" class="rounded-full">
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
