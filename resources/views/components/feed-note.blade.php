<a class="block" href="/notes/{{ $note->slug }}">
    <article class="border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4">
        <p>{{ $note->notebody }}</p>
        <footer class="mt-4">
            <span>{{ $note->created_at->diffForHumans() }}</span>
        </footer>
    </article>
</a>
