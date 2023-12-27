<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    <div class="flex justify-between items-center">
        <h2 class="medium-title mb-4 dark:text-white text-white drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Products</h2>
        @if (auth()->check() &&
                auth()->user()->id === $user->id &&
                !auth()->user()->isBanned())
            <x-new-product-modal></x-new-product-modal>
        @endif
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($products as $product)
            @if ($product->active || (auth()->check() && auth()->user()->id == $user->id))
                <x-product :product="$product" :user="$user" />
            @endif
        @endforeach
    </div>
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</x-profile-layout>
