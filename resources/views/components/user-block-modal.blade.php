@props(['user'])

<x-modal name="block" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Block User <span class="font-bold">{{ $user->username }}</span>
        </h2>

        <form action="{{ route('block') }}" method="POST">
            @csrf

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <x-input-label>Are you sure you want to block {{ $user->username }}? They won't be able to see anything you post or write.</x-input-label>

            <x-danger-button class="mt-4">Block</x-danger-button>
        </form>

    </div>
</x-modal>
