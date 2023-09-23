<x-main-layout>
    <div class="note-wrapper">
        <article
            class="border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4">
            <div class="text-lg mb-4">
                <p>{{ $note->notebody }}</p>
            </div>
            <div class="flex items-center justify-between">
                <span> by <a href="/u/{{ $note->author->username }}">{{ $note->author->username }}</a>
                    {{ $note->created_at->diffForHumans() }}</span>
                @if (auth()->check() && $note->author->id === auth()->user()->id)
                    <form action="{{ route('note.delete', $note) }}" method="POST">
                        {{-- @method('delete') --}}
                        @csrf
                        <input type="hidden" name="id" value="{{ $note->id }}">
                        <x-danger-button href="/note/delete">Delete Note</x-danger-button>
                    </form>
                @endif
            </div>
            <a href="{{ url()->previous() }}" class="inline-block mt-4 p-2"><i
                    class="mr-1 fa-solid fa-arrow-left"></i>Go
                back</a>
        </article>
    </div>
</x-main-layout>
