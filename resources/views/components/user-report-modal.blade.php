@props(['user'])

<x-modal name="report" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Report User <span class="font-bold">{{ $user->username }}</span>
        </h2>

        <form action="{{ route('report', $user->id) }}" method="POST">
            @csrf

            <x-input-label for="reason">Report for: </x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason"></x-textarea>

            <x-danger-button class="mt-4">Report</x-danger-button>
        </form>

    </div>
</x-modal>
