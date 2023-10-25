@props(['item', 'type'])

<x-starshop-layout>
    <a href="{{ url()->previous() }}" class="block pb-2"><i class="mr-1 fa-solid fa-arrow-left"></i>Go
        back</a>
    <article class=" bg-white dark:bg-neutral-800 rounded-md shadow-sm p-4 mb-6">

        {{ $slot }}

        <div class="mt-2 grid grid-cols-4">
            <div class="col-span-3">
                <div class="flex items-center space-x-2">
                    <h1 class="large-title">
                        {{ $item->name }}
                    </h1>
                    <div class="flex space-x-1 pt-2">
                        <span>{{ $item->likes->count() }}</span>
                        @if (auth()->check())
                            <form action="{{ route($type . '.like', $item->id) }}" method="POST">
                                @csrf @method('POST')
                                <button type="submit">
                                    @if ($item->isLiked($item))
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
                <p><a href="{{ route('profile.index', $item->author->username) }}">added by
                        {{ $item->author->username }}</a></p>

                <div class="text-lg my-2">
                    <p>{{ $item->description }}</p>
                </div>

                <div class="flex justify-between">
                    <ul class="flex">
                        tags here
                        {{-- @foreach ($wallpaper->tags as $tag)
                                        <li class="mr-1"><a
                                                class="px-2 py-0.5 rounded-md bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-400 inline-block"
                                                href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a></li>
                                    @endforeach --}}
                    </ul>
                </div>
            </div>
            @auth
                <div class="text-right flex flex-col justify-between mt-2">

                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <x-secondary-button type="submit" class="ml-2 !px-2 h-7 w-7"><i
                                    class="fa-solid fa-ellipsis"></i></x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->check() && $item->author->id === auth()->user()->id)
                                <form action="{{ route($type . '.delete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button onclick="return confirm('Are you sure you want to delete this?')"
                                        class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                        Delete {{ ucwords(str_replace('-', ' ', $type)) }}
                                    </button>
                                </form>
                            @elseif (auth()->check() && $item->author->id !== auth()->user()->id)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'post-report')"
                                    class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                    {{ __('Report') }}
                                </button>
                            @endif
                        </x-slot>
                    </x-dropdown>
                    <div class="flex space-x-2 justify-end items-center">
                        <p class="text-lg">{{ $item->price }} <i class="fa-solid fa-star"></i></p>
                        @if (!auth()->user()->hasItem($item->id, $type))
                            <form action="{{ route('starshop.' . $type . 's.buy') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <x-primary-button>Buy</x-primary-button>
                            </form>
                        @else
                            <x-secondary-button disabled>Bought</x-secondary-button>
                        @endif
                    </div>
                </div>
            @endauth
        </div>

    </article>

    {{-- <x-post-report-modal :post="$post"></x-post-report-modal> --}}
</x-starshop-layout>