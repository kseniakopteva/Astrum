    <article
        class="border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 break-inside-avoid mb-4">
        <div class="flex justify-between mb-4">
            <div class="flex space-x-2">
                <x-colored-username-link size="big" :user="$note->author"></x-colored-username-link>

                @if (!is_null($note->parent_id))
                    <?php $parent = App\Models\Note::find($note->parent_id); ?>
                    <p>
                        <a
                            href={{ route('note.show', ['author' => $parent->author->username, 'note' => $parent->slug]) }}>
                            responding to this note
                        </a>
                    </p>
                @endif
            </div>
            <a href={{ route('note.show', ['author' => $note->author->username, 'note' => $note->slug]) }}>
                {{ $note->created_at->diffForHumans() }}
            </a>

        </div>
        <a href={{ route('note.show', ['author' => $note->author->username, 'note' => $note->slug]) }}>
            <p>{{ $note->notebody }}</p>
        </a>

        <footer class="mt-4 flex justify-between">
            <a href="{{ route('note.show', ['author' => $note->author, 'note' => $note]) }}"
                class="space-x-1"><span>{{ $note->comments->count() }}</span><i class="fa-regular fa-comment"></i></a>


            <x-likes route="note.like" :item="$note"></x-likes>
        </footer>
    </article>
