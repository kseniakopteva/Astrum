<x-main-layout>
    <div class="wrapper">
        <article class="border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4">
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
    </div>
</x-main-layout>
