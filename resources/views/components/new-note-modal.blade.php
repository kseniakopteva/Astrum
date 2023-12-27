@if ($errors->getBag('default')->has('notebody'))
    <div x-data="{ open: true }">
        <?php $show = true; ?>
    @else
        <div x-data="{ open: false }">
            <?php $show = false; ?>
@endif

<x-secondary-button class="ml-4" x-on:click.prevent="$dispatch('open-modal', 'open-new-note')">New
    Note</x-secondary-button>

<x-modal name="open-new-note" :show="$show" focusable>
    <div class="shadow-lg bg-neutral-100 dark:bg-neutral-800 p-4 rounded-md overflow-hidden">
        <form method="POST" action="{{ route('note.store') }}" class="space-y-2">
            <div class="flex justify-between items-center">
                <h2 class="small-title">New Note</h2>
                <span class="dark:text-red-300 text-red-600">
                    Cost: <x-price>5</x-price>
                </span>
            </div>
            @csrf
            <div class="flex-grow">
                <x-textarea class="h-auto" rows="6" id="notebody" class="block mt-1 w-full" type="text"
                    name="notebody" placeholder="Note text" value="{{ old('notebody') }}" required />
                <x-input-error :messages="$errors->get('notebody')" class="mt-2" />
            </div>
            <div class="flex-grow">
                <x-text-input class="h-auto" id="tags" class="block w-full" type="text" name="tags"
                    placeholder="Tags (separate with comma)" value="{{ old('tags') }}" />
                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
            </div>
            <div class="pt-4 flex justify-between">
                <x-secondary-button type="reset">Clear</x-secondary-button>
                <x-primary-button class="w-48 justify-center overflow-hidden">Create note</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
    </div>
