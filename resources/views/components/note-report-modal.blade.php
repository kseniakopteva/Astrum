@props(['note'])

<x-modal name="note-report" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Report Note <span
                class="font-bold">[{{ implode(' ', array_slice(explode(' ', $note->notebody), 0, 5)) . '...' }}]</span>
            by <a href="{{ route('profile.index', $note->author->username) }}"><span
                    class="font-bold">{{ $note->author->username }}</span></a>
        </h2>

        <form action="{{ route('report.note', $note->slug) }}" method="POST">
            @csrf @method('post')

            <input type="hidden" name="reported_id" value="{{ $note->id }}">

            <x-input-label for="reason">Report for: </x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason" required></x-textarea>

            <x-danger-button class="mt-4">Report</x-danger-button>
        </form>

    </div>
</x-modal>
