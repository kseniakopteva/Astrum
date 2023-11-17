@props(['route', 'item', 'textarea_name'])

<form action="{{ route($route, $item->id) }}" method="post" x-data="{ open: false }"
    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 rounded-md">
    @csrf
    <header class="flex items-center cursor-pointer p-4" x-on:click="open = ! open">
        <img src="{{ asset('images/profile-pictures/' . auth()->user()->image) }}" alt="" width="40"
            height="40" class="rounded-full">
        <h2 class="ml-4">Join the discussion...</h2>
    </header>
    <div x-show="open" class=" px-4 pb-4 -mt-4">
        <div class="mt-6">
            <x-textarea class="w-full" name="{{ $textarea_name }}" rows="5"
                placeholder="Write something polite and constructive here..." required></x-textarea>
            <x-input-error :messages="$errors->get('{{ $textarea_name }}')"></x-input-error>
        </div>
        <div class="flex justify-end"><x-primary-button>Post</x-primary-button></div>
    </div>
</form>
