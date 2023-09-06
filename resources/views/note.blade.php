<x-main-layout>
    <div class="wrapper">
        <article class="border border-neutral-200 rounded-md shadow-sm p-4">
            <div class="text-lg mb-4">
                <p>{{ $note->notebody }}</p>
            </div>
            <div class="note-footer">
                by <a href="/u/{{ $note->author->username }}">{{ $note->author->username }}</a>
                {{ $note->created_at->diffForHumans() }}
            </div>
            <a href="{{ url()->previous() }}" class="inline-block mt-4 p-2"><i class="mr-1 fa-solid fa-arrow-left"></i>Go
                back</a>
        </article>
    </div>
</x-main-layout>
