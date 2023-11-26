<x-profile-layout :user="$user" :notes="$notes" :followers="$followers" :following="$following">
    <div>
        <div class="max-w-xl m-auto">
            <h2 class="medium-title mb-4 dark:text-white text-white drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Notes</h2>
            @foreach ($notes as $note)
                <x-feed-note class="cols-1" :note=$note :user=$user></x-feed-note>
            @endforeach
        </div>
</x-profile-layout>
