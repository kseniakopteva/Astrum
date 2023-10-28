<x-profile-layout :user="$user" :notes="$notes" :followers="$followers" :following="$following">
    <div>
        <h2 class="medium-title mb-4 dark:text-white">Notes</h2>
        <div class="columns lg:columns-3 overflow-hidden mb-4">
            @foreach ($notes as $note)
                <x-feed-note class="cols-1" :note=$note :user=$user></x-feed-note>
            @endforeach
        </div>
</x-profile-layout>
