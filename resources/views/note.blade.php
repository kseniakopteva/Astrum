<x-main-layout>
    <div class="note-wrapper">
        <div class="">
            @foreach ($ancestors as $n)
                <article
                    class="border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4">
                    @if (!$n->removed)
                        <div class="mb-4"><a
                                href={{ route('note.show', ['author' => $n->author->username, 'note' => $n->slug]) }}>
                                <p>{{ $n->notebody }}</p>
                            </a>
                        </div>
                        <div class="flex items-center justify-between">
                            <span> by <a href="/u/{{ $n->author->username }}">{{ $n->author->username }}</a>
                                {{ $n->created_at->diffForHumans() }}</span>
                        @else
                            <div class="h-6 italic flex items-center justify-center">Note deleted</div>
                    @endif
                </article>
                <div class="relative bottom-0 left-8 border-l-2 border-neutral-600 h-4"></div>
            @endforeach
        </div>
        <article id="current"
            class="border border-neutral-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 rounded-md shadow-sm p-4">
            <div class="text-lg mb-4">
                <p>{{ $note->notebody }}</p>
            </div>
            <div class="flex items-center justify-between">
                <span> by <a href="/u/{{ $note->author->username }}">{{ $note->author->username }}</a>
                    {{ $note->created_at->diffForHumans() }}</span>
                @auth
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <x-secondary-button type="submit" class="ml-2 !px-2"><i
                                    class="fa-solid fa-ellipsis"></i></x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->check() && $note->author->id === auth()->user()->id)
                                <form action="{{ route('note.delete', $note) }}" method="POST" class="px-1">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $note->id }}">
                                    <x-danger-button href="/note/delete">Delete Note</x-danger-button>
                                </form>
                            @elseif (auth()->check() && $note->author->id !== auth()->user()->id)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'note-report')"
                                    class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                    {{ __('Report') }}
                                </button>
                            @endif
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>
            {{-- <a href="{{ url()->previous() }}" class="inline-block mt-4 p-2"><i
                    class="mr-1 fa-solid fa-arrow-left"></i>Go
                back</a> --}}
        </article>

        <section class="space-y-4 max-w-3xl m-auto mt-6 mb-96">
            @auth
                <form action={{ route('note.comment.store', $note->id) }} method="post"
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 p-4 rounded-md">
                    @csrf
                    <header class="flex items-center">
                        <img src="{{ asset('storage/images/profile-pictures/' . auth()->user()->image) }}" alt=""
                            width="40" height="40" class="rounded-full">
                        <h2 class="ml-4">Join the discussion...</h2>
                    </header>
                    <div class="mt-4">
                        <x-textarea class="w-full" name="notebody" rows="3"
                            placeholder="Write something polite and constructive here..." required></x-textarea>
                        <x-input-error :messages="$errors->get('notebody')"></x-input-error>
                    </div>
                    <div class="flex justify-end"><x-primary-button>Post</x-primary-button></div>
                </form>
            @else
                <p><a href="/register" class="underline">Register</a> or <a href="/login" class="underline">log in</a> to
                    leave a comment!</p>
            @endauth
            @foreach ($note->comments as $comment)
                <article
                    class="flex max-w-3xl m-auto bg-neutral-100 border border-neutral-200
                     dark:bg-neutral-800 dark:border-neutral-700 p-4 rounded-md space-x-4">

                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/images/profile-pictures/' . $comment->author->image) }}"
                            alt="" width="60" height="60" class="rounded-full">
                    </div>
                    <div>
                        <header class="mb-4">
                            <h3 class="font-bold">
                                <a href="/u/{{ $comment->author->username }}">
                                    {{ $comment->author->username }}
                                </a>
                            </h3>
                            <p class="text-xs">
                                Posted on <time>{{ $comment->created_at->format('M j, Y, H:i') }}</time>
                            </p>
                        </header>
                        <p>
                            <a
                                href={{ route('note.show', ['author' => $comment->author->username, 'note' => $comment->slug]) }}>
                                {{ $comment->notebody }}
                            </a>
                        </p>
                    </div>
                </article>
            @endforeach
        </section>
    </div>
    <x-note-report-modal :note="$note"></x-note-report-modal>
</x-main-layout>
