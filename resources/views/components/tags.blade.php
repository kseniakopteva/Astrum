@props(['item'])

<ul class="flex">
    @foreach ($item->tags as $tag)
        <li class="mr-1"><a class="px-2 py-0.5 rounded-md bg-lime-200 dark:bg-lime-800 dark:text-neutral-300 inline-block" href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a></li>
    @endforeach
</ul>
