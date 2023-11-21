@props(['comment'])

<x-panel :item="$comment">
    <div class="flex space-x-4">
        <div class="flex-shrink-0">
            <img src="{{ asset('images/profile-pictures/' . $comment->author->image) }}" alt="" width="60" height="60" class="rounded-full">
        </div>
        <div class="w-full">
            <header class="mb-4 flex justify-between">
                <div>
                    <h3 class="font-bold">
                        <x-colored-username-link size="small" :user="$comment->author"></x-colored-username-link>
                    </h3>
                    <p class="text-xs">
                        Posted on <time>{{ $comment->created_at->format('M j, Y, H:i') }}</time>
                    </p>
                </div>
                @auth
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <x-secondary-button type="submit" class="ml-2 !px-2"><i class="fa-solid fa-ellipsis"></i></x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->check() && $comment->author->id === auth()->user()->id)
                                <form action="{{ route('post.comment.delete', ['comment' => $comment->id, 'post' => $comment->post->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $comment->id }}">
                                    <button onclick="return confirm('Are you sure you want to delete this?')"
                                        class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                        Delete Comment
                                    </button>
                                </form>
                            @elseif (auth()->check() && $comment->author->id !== auth()->user()->id)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'comment-report-{{ $comment->id }}')"
                                    class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                    {{ __('Report') }}
                                </button>
                            @endif
                        </x-slot>
                    </x-dropdown>
                @endauth
            </header>
            <p>{{ $comment->body }}</p>
            <footer class="w-full flex justify-end">

                <x-likes route="postcomment.like" :item="$comment"></x-likes>
            </footer>
        </div>
    </div>
</x-panel>
<x-comment-report-modal :comment="$comment" name="comment-report-{{ $comment->id }}" />
