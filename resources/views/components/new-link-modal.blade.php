@if ($errors->getBag('default')->has('name') || $errors->getBag('default')->has('link'))
<div x-data="{ open: true }">
    <?php $show = true; ?>
    @else
    <div x-data="{ open: false }">
        <?php $show = false; ?>
        @endif

        <x-secondary-button x-on:click.prevent="$dispatch('open-modal', 'open-new-link')">Add new Link</x-secondary-button>


        <x-modal name="open-new-link" :show="$show" focusable>
            <div class="shadow-lg bg-neutral-100 dark:bg-neutral-800 p-4 rounded-md overflow-hidden">
                <form method="POST" action="{{ route('about.link.store') }}" class="space-y-2">
                    <h2 class="small-title">New Social Link</h2>
                    @csrf
                    <div class="flex-grow">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="flex-grow">
                        <x-input-label for="link" :value="__('Link')" />
                        <x-text-input id="link" class="block mt-1 w-full" type="url" name="link" :value="old('link')" required autofocus autocomplete="link" />
                        <x-input-error :messages="$errors->get('link')" class="mt-2" />
                    </div>
                    <div class="pt-4 flex justify-between">
                        <x-secondary-button type="reset">Clear</x-secondary-button>
                        <x-primary-button class="w-48 justify-center overflow-hidden">Submit</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
