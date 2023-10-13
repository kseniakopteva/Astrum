    <article
        class="border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 break-inside-avoid mb-4">

        @if (!is_null($note->parent_id))
            <?php $parent = App\Models\Note::find($note->parent_id); ?>
            <p class="mb-4">
                <a href={{ route('note.show', ['author' => $parent->author->username, 'note' => $parent->slug]) }}>[
                    View original note ]</a>

            </p>
        @endif
        <a href={{ route('note.show', ['author' => $note->author->username, 'note' => $note->slug]) }}>
            <p>{{ $note->notebody }}</p>
        </a>

        <a href={{ route('note.show', ['author' => $note->author->username, 'note' => $note->slug]) }}>
            <footer class="mt-4">
                <span>{{ $note->created_at->diffForHumans() }}</span>
            </footer>
        </a>
    </article>
