<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    @if (auth()->check() && auth()->user()->id === $user->id && !auth()->user()->isBanned())
    <x-new-product-modal></x-new-product-modal>
    @endif

    <div class="grid grid-cols-3 gap-5 mb-10">
        @foreach ($products_e as $product)
        <div class="rounded-md bg-white">
            <div class="">
                <img class="rounded-t-md h-56 w-full object-cover" src="{{ asset('storage/images/products/'.$product->image) }}" alt="">
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div class="text-lg">{{ $product->name }}</div>
                    <div class="text-xl">
                        {{ $product->price }} €
                    </div>
                </div>
                <div class="my-2">{{ $product->description }}</div>
                @if ($product->type === 'unlimited')
                <div>{{ $product->available_slots }} Slots available out of {{ $product->max_slots }}</div>
                @endif
            </div>
        </div>
        @endforeach
        @foreach ($products_s as $product)
        <div class="rounded-md bg-white">
            <div class="">
                <img class="rounded-t-md h-56 w-full object-cover" src="{{ asset('storage/images/products/'.$product->image) }}" alt="">
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div class="text-lg">{{ $product->name }}</div>
                    <div class="text-xl">
                        {{ $product->price }}
                        <x-star-icon />
                    </div>
                </div>
                <div class="my-2">{{ $product->description }}</div>
                @if ($product->type === 'unlimited')
                <div>{{ $product->available_slots }} Slots available out of {{ $product->max_slots }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</x-profile-layout>
