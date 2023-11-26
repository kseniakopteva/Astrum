<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    @push('preloads')
        @foreach ($posts as $post)
            <link rel="preload" href="{{ asset('images/posts/' . $post->image) }}" as="image">
        @endforeach
    @endpush

    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-3 md:col-span-2">
            <div class="flex">
                <h2 class="medium-title mb-4 dark:text-white text-white drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Recent Posts</h2>
                @if (auth()->check() &&
                        auth()->id() === $user->id &&
                        !auth()->user()->isBanned())
                    <div>
                        <x-new-post-modal></x-new-post-modal>

                    </div>
                @endif
            </div>

            <div class="masonry">
                @foreach ($posts as $post)
                    <x-feed-post class="cols-1" :post=$post></x-feed-post>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
        <div class="hidden md:block">
            <div class="flex">
                <h2 class="medium-title mb-4 dark:text-white text-white drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Recent Notes</h2>
                @if (auth()->check() &&
                        auth()->id() === $user->id &&
                        !auth()->user()->isBanned())
                    <div>
                        <x-new-note-modal></x-new-note-modal>
                    </div>
                @endif
            </div>
            <div class="space-y-4 mb-4">
                @foreach ($notes as $note)
                    <x-feed-note :note=$note :user=$user></x-feed-note>
                @endforeach
                <div class="text-center pt-4">
                    @if ($user->notes->count() > 20)
                        <a href="{{ route('profile.notes', $user->username) }}"
                            class="cursor-pointer inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150">
                            View all notes</a>
                    @endif
                </div>
            </div>
        </div>
</x-profile-layout>
<script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
<script>
    var macy_instance = Macy({
        container: '.masonry',
        trueOrder: false,
        waitForImages: true,
        debug: true,
        margin: 10,
        columns: 2,
        breakAt: {
            1024: 1,
            768: 2,
            640: 1
        }
    });

    macy_instance.runOnImageLoad(function() {
        macy_instance.recalculate(true);
    }, true);
</script>
