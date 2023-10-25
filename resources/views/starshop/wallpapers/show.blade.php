<x-starshop-layout>
    <a href="{{ url()->previous() }}" class="block pb-2"><i class="mr-1 fa-solid fa-arrow-left"></i>Go
        back</a>
    <article class=" bg-white dark:bg-neutral-800 rounded-md shadow-sm p-4 mb-6">
        <div class="border-b border-neutral-200 dark:border-neutral-700 pb-16">
            <img class="h-[calc(100vh-25rem)] m-auto shadow-lg rounded-lg border border-neutral-900"
                src="{{ asset('storage/images/wallpapers/' . $wallpaper->image) }}" alt="">
        </div>
        <div class="">

            <div class="flex flex-col">


                <div class="flex justify-between mt-2 items-center">
                    <h1 class="large-title">
                        {{ $wallpaper->name }}
                    </h1>
                    @auth
                        <div class="text-right flex">
                            @if (!auth()->user()->hasWallpaper($wallpaper->id))
                                <form action="{{ route('starshop.wallpapers.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $wallpaper->id }}">
                                    <x-primary-button>Buy</x-primary-button>
                                </form>
                            @else
                                <x-secondary-button disabled>Bought</x-secondary-button>
                            @endif
                            <x-dropdown align="right" width="52">
                                <x-slot name="trigger">
                                    <x-secondary-button type="submit" class="ml-2 !px-2 h-7 w-7"><i
                                            class="fa-solid fa-ellipsis"></i></x-secondary-button>
                                </x-slot>

                                <x-slot name="content">
                                    @if (auth()->check() && $wallpaper->author->id === auth()->user()->id)
                                        <form action="{{ route('wallpaper.delete') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $wallpaper->id }}">
                                            <button
                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                                Delete Wallpaper
                                            </button>
                                        </form>
                                    @elseif (auth()->check() && $wallpaper->author->id !== auth()->user()->id)
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
                </div>
                <p><a href="{{ route('profile.index', $wallpaper->author->username) }}">added by
                        {{ $wallpaper->author->username }}</a></p>

                <div class="text-lg my-2">
                    <p>{{ $wallpaper->description }}</p>
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

                    <div class="flex space-x-1">
                        <span>{{ $wallpaper->likes->count() }}</span>
                        @if (auth()->check())
                            <form action="{{ route('wallpaper.like', $wallpaper->id) }}" method="POST">
                                @csrf @method('POST')
                                <button type="submit">
                                    @if ($wallpaper->isLiked($wallpaper))
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

    {{-- <x-post-report-modal :post="$post"></x-post-report-modal> --}}
</x-starshop-layout>
