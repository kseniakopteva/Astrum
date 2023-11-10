<x-profile-layout :user="$user" :notes="$notes" :followers="$followers" :following="$following">
    <div>
        <h2 class="medium-title mb-4 dark:text-white">Notes</h2>
        {{-- <div class="columns lg:columns-3 overflow-hidden mb-4"> --}}
        <div class="masonry">
            @foreach ($notes as $note)
                <x-feed-note class="cols-1" :note=$note :user=$user></x-feed-note>
            @endforeach
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
        columns: 3,
        breakAt: {
            1024: 3,
            768: 2,
            640: 1
        }
    });

    macy_instance.runOnImageLoad(function() {
        macy_instance.recalculate(true);
    }, true)
</script>
