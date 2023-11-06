@props(['comment', 'name'])

<x-modal name="{{ $name }}" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Report Comment <span
                class="font-bold">[{{ implode(' ', array_slice(explode(' ', $comment->body), 0, 5)) }}...]</span>
            by <a href="/u/{{ $comment->author->username }}"><span
                    class="font-bold">{{ $comment->author->username }}</span></a>
        </h2>

        <form action="{{ route('report.comment', $comment->id) }}" method="post">
            @csrf @method('post')

            <input type="hidden" name="reported_id" value="{{ $comment->id }}">

            <x-input-label for="reason">Report for: </x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason" required></x-textarea>

            <x-danger-button class="mt-4">Report</x-danger-button>
        </form>

    </div>
</x-modal>
