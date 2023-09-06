<a class="block" href="/notes/{{ $note->slug }}">
    <article class="border border-neutral-200 rounded-lg p-6">
        <p>{{ $note->notebody }}</p>
        <footer class="note-footer">
            <span>{{ $note->created_at->diffForHumans() }}</span>
        </footer>
    </article>
</a>
