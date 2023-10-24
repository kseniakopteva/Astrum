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

        <footer class="mt-4 flex justify-between">
            <a href={{ route('note.show', ['author' => $note->author->username, 'note' => $note->slug]) }}>
                <span>{{ $note->created_at->diffForHumans() }}</span>
            </a>
            <div class="flex space-x-1">
                <span>{{ $note->likes->count() }}</span>
                @if (auth()->check())
                    <form action="{{ route('note.like', $note->id) }}" method="POST">
                        @csrf @method('POST')
                        <button type="submit">
                            @if ($note->isLiked($note))
                                <i class="fa-solid fa-heart"></i>
                            @else
                                <i class="fa-regular fa-heart"></i>
                            @endif
                        </button>
                    </form>
                @else
                    <span>Likes</span>
                @endif
            </div>
        </footer>
    </article>
