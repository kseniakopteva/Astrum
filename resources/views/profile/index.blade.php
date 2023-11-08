<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-3 md:col-span-2">
            <div class="flex">
                <h2 class="medium-title mb-4 dark:text-white">Recent Posts</h2>
                @if (!auth()->user()->isBanned(auth()->user()))
                    <div>
                        @if (auth()->id() === $user->id)
                            <x-new-post-modal></x-new-post-modal>
                        @endif
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
                <h2 class="medium-title mb-4 dark:text-white">Recent Notes</h2>
                @if (!auth()->user()->isBanned(auth()->user()))
                    @if (auth()->id() === $user->id)
                        <div><x-new-note-modal></x-new-note-modal></div>
                    @endif
                @endif
            </div>
            <div class="space-y-4 mb-4">
                @foreach ($notes as $note)
                    <x-feed-note :note=$note :user=$user></x-feed-note>
                @endforeach
                @if ($user->notes->count() > 20)
                    <x-secondary-button>View all notes</x-secondary-button>
                @endif
            </div>
        </div>
</x-profile-layout>
<script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
<script>
    var macy_instance = Macy.init({
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
