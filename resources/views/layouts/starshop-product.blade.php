@props(['item', 'type'])

<x-starshop-layout>
    <a href="{{ url()->previous() }}" class="block pb-2"><i class="mr-1 fa-solid fa-arrow-left"></i>Go
        back</a>
    <article class="rounded-md p-6 mb-6">

        {{ $slot }}

        <div class="mt-2 grid grid-cols-4 p-6">
            <div class="col-span-3 mb-10">
                <div class="flex items-center space-x-2">
                    <h1 class="large-title pb-1 pr-2">
                        {{ $item->name }}
                    </h1>

                    <x-likes :route="$type . '.like', $item->id" :item="$item" :button="true"></x-likes>

                </div>
                <p><span>added by <x-colored-username-link size="small" :user="$item->author"></x-colored-username-link></span></p>

                <div class="text-lg my-4">
                    <p>{{ $item->description }}</p>
                </div>

                {{-- @if (!$item->tags->isEmpty())
                    <div class="flex justify-between">
                        <x-tags :item="$item"></x-tags>
                    </div>
                @endif --}}
            </div>
            @auth
                <div class="text-right flex flex-col justify-between mt-2">

                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <x-secondary-button type="submit" class="ml-2 !px-2 h-7 w-7"><i class="fa-solid fa-ellipsis"></i></x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->check() && $item->author->id === auth()->user()->id)
                                <form action="{{ route($type . '.delete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button onclick="return confirm('Are you sure you want to delete this?')"
                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                        Delete {{ ucwords(str_replace('-', ' ', $type)) }}
                                    </button>
                                </form>
                            @elseif (auth()->check() && $item->author->id !== auth()->user()->id)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'post-report')"
                                    class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                    {{ __('Report') }}
                                </button>
                            @endif
                        </x-slot>
                    </x-dropdown>
                    <div class="flex space-x-2 justify-end items-center">
                        <p class="text-lg"><x-price>{{ $item->price }}</x-price></p>
                        @if ($type != 'post-frame')
                            @if (!auth()->user()->hasItem($item->id, $type))
                                <form action="{{ route('starshop.' . $type . 's.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <x-primary-button>Buy</x-primary-button>
                                </form>
                            @else
                                <x-secondary-button disabled>Bought</x-secondary-button>
                            @endif
                        @else
                            @if (is_null(auth()->user()->postFrameAmount($item->id)) &&
                                    auth()->user()->postFrameAmount($item->id) <= 0)
                                <form action="{{ route('starshop.' . $type . 's.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <x-primary-button>Buy</x-primary-button>
                                </form>
                            @else
                                <form action="{{ route('starshop.' . $type . 's.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <x-secondary-button type="submit">Buy another
                                        ({{ auth()->user()->postFrameAmount($item->id) }})</x-secondary-button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endauth
        </div>

    </article>

    <x-starshop-product-report-modal :item="$item" :type="$type" />
</x-starshop-layout>
