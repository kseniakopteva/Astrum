@props(['route', 'item', 'textarea_name'])


<x-panel :item="null" :darker="true">
    <form action="{{ route($route, $item->id) }}" method="post" x-data="{ open: false }">
        @csrf
        <header class="flex items-center cursor-pointer" x-on:click="open = ! open">
            <img src="{{ asset('images/profile-pictures/' . auth()->user()->image) }}" alt="" width="40" height="40" class="rounded-full">
            <h2 class="ml-4">Join the discussion...</h2>
        </header>
        <div x-show="open" class="-mt-4">
            <div class="mt-6">
                <x-textarea class="w-full" name="{{ $textarea_name }}" rows="5" placeholder="Write something polite and constructive here..." required></x-textarea>
                <x-input-error :messages="$errors->get('{{ $textarea_name }}')"></x-input-error>
            </div>
            <div class="flex justify-end gap-5 items-center">
                <x-price>Cost to comment: 1</x-price>
                <x-primary-button>Create comment</x-primary-button>
            </div>
        </div>
    </form>
</x-panel>
