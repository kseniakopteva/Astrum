<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    @if (auth()->check() &&
            auth()->user()->id === $user->id &&
            !auth()->user()->isBanned())
        <x-new-product-modal></x-new-product-modal>
    @endif

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        @foreach ($products as $product)
            <x-product :product="$product" :user="$user" />
        @endforeach
    </div>
</x-profile-layout>
