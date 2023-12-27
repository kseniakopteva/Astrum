@props(['product', 'user'])

@php
    if ($product->type === 'unlimited') {
        $buy = 'Get';
    } else {
        $buy = 'Buy';
    }
@endphp
<div class="rounded-md bg-white dark:bg-neutral-800 relative">
    <img class="cursor-pointer rounded-t-md h-56 w-full object-cover" src="{{ asset('images/products/' . $product->image) }}" alt="" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'image-{{ $product->id }}')">

    @if (!$product->active)
        <div class="inline-block absolute top-3 left-3 p-2 bg-red-200 rounded-md border border-red-400">Only you can see this product</div>
    @endif

    @if (auth()->check() && auth()->user()->id === $user->id)
        <x-dropdown align="right" width="52" absolute="true">
            <x-slot name="trigger">
                <x-secondary-button type="submit" class="ml-2 !px-2"><i class="fa-solid fa-ellipsis"></i></x-secondary-button>
            </x-slot>

            <x-slot name="content">
                <form action="{{ route('product.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button onclick="return confirm('Are you sure you want to delete this?')"
                        class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                        Delete Product
                    </button>
                </form>

                @if (!$product->active)
                    <form action="{{ route('product.active') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button
                            class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out">
                            Make active again
                        </button>
                    </form>
                @endif
            </x-slot>
        </x-dropdown>
    @endif
    <div class="p-4">
        <div class="flex justify-between items-center">
            <div class="text-lg">{{ $product->name }}</div>
            <div class="text-xl">
                {{ $product->price }}
                @if ($product->currency == 'euro')
                    €
                @else
                    <x-star-icon />
                @endif
            </div>
        </div>
        <div class="my-2">{{ $product->description }}</div>

        <div class="flex justify-between items-center">
            <div class="px-2 py-0.5 rounded-md @if (!is_null($user->colour)) bg-{{ $user->colour->lightcolor }}/30 dark:bg-{{ $user->colour->darkcolor }}/30 @else bg-lime-500/30 @endif">
                @if ($product->type === 'unlimited')
                    @if (!$product->max_slots)
                        Infinite slots available
                    @else
                        {{ $product->availableSlots() }} slots available out of {{ $product->max_slots }}
                    @endif
                @else
                    One-time purchase
                @endif
            </div>

            @auth
                {{--

                Button is active if
                1. Unlimited type AND max_slots is null
                2. Unlimited type AND max_slots NOT null AND availableSlots NOT 0
                3. Limited AND active

                --}}

                @if (
                    ($product->type === 'unlimited' && !$product->max_slots) ||
                        ($product->type === 'unlimited' && $product->max_slots && $product->availableSlots() != 0) ||
                        ($product->type == 'one-time' && $product->active))
                    <x-primary-button class="ml-auto" x-data="" type="button"
                        x-on:click.prevent="$dispatch('open-modal', 'buy-product-{{ $product->id }}')">{{ $buy }}</x-primary-button>
                @else
                    <x-primary-button class="ml-auto opacity-50" x-data="" type="button" disabled
                        x-on:click.prevent="$dispatch('open-modal', 'buy-product-{{ $product->id }}')">{{ $buy }}</x-primary-button>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="ml-auto inline-flex items-center px-4 py-2 bg-lime-800 dark:bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-neutral-800 uppercase tracking-widest hover:bg-lime-700 dark:hover:bg-white focus:bg-lime-700 dark:focus:bg-white active:bg-lime-900 dark:active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">{{ $buy }}</a>
            @endauth
        </div>
    </div>
</div>
<x-modal name="image-{{ $product->id }}" focusable maxWidth='5xl'>
    <div class="p-4">
        <img class="rounded-t-md w-full object-cover" src="{{ asset('images/products/' . $product->image) }}" alt="">
    </div>

</x-modal>
@auth
    @php

        if ($errors->getBag('default')->has('Email') || $errors->getBag('default')->has('details')) {
            $show = true;
        } else {
            $show = false;
        }
    @endphp
    <x-modal name="buy-product-{{ $product->id }}" focusable :show="$show">
        <div class="p-6">
            <h2 class="text-lg font-medium dark:text-neutral-100 mb-4">
                {{ $buy }} <span class="font-bold">{{ $product->name }}</span> by <a href="{{ route('profile.index', $product->author->username) }}"><span
                        class="font-bold">{{ $product->author->username }}</span></a>
            </h2>

            <form action="{{ route('product.buy', $product->slug) }}" method="POST">
                @csrf @method('post')

                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="buyer_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="seller_id" value="{{ $user->id }}">

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <x-input-label for="details" class="mt-2">Details </x-input-label>
                <x-textarea class="w-full mt-1" name="details" id="details" required></x-textarea>
                <x-input-error :messages="$errors->get('details')" class="mt-2" />

                <x-primary-button class="mt-4">{{ $buy }}</x-primary-button>
            </form>

        </div>
    </x-modal>
@endauth
