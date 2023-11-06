@props(['item', 'type'])

<x-modal name="post-report" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Report {{ ucwords(str_replace('-', ' ', $type)) }} <span class="font-bold">[{{ $item->name }}]</span> by <a
                href="/u/{{ $item->author->username }}"><span class="font-bold">{{ $item->author->username }}</span></a>
        </h2>

        <form action="{{ route('report.starshop.product', $item->slug) }}" method="POST">
            @csrf @method('post')

            <input type="hidden" name="reported_id" value="{{ $item->id }}">
            <input type="hidden" name="type" value="{{ $type }}">

            <x-input-label for="reason">Report for: </x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason" required></x-textarea>

            <x-danger-button class="mt-4">Report</x-danger-button>
        </form>

    </div>
</x-modal>
