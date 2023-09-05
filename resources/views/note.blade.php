<x-main-layout>
    <article class="note">
        {{ $note->body }}
        <div class="note-footer">
            by <a href="/u/{{ $note->author->username }}">{{ $note->author->username }}</a>
            {{ $note->created_at->diffForHumans() }}
        </div>
    </article>
</x-main-layout>
