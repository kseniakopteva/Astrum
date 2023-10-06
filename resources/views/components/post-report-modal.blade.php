@props(['post'])

<x-modal name="post-report" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Report Post <span class="font-bold">[{{ $post->title }}]</span> by <a
                href="/u/{{ $post->author->username }}"><span class="font-bold">{{ $post->author->username }}</span></a>
        </h2>

        <form action="{{ route('report', $post->id) }}" method="POST">
            @csrf

            <x-input-label for="reason">Report for: </x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason"></x-textarea>

            <x-danger-button class="mt-4">Report</x-danger-button>
        </form>

    </div>
</x-modal>
