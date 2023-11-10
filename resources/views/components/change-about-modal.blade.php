@if ($errors->getBag('default')->has('about'))
<div x-data="{ open: true }">
    <?php $show = true; ?>
    @else
    <div x-data="{ open: false }">
        <?php $show = false; ?>
        @endif

        <x-secondary-button x-on:click.prevent="$dispatch('open-modal', 'change-about-link')">Change About</x-secondary-button>


        <x-modal name="change-about-link" :show="$show" focusable>
            <div class="shadow-lg bg-neutral-100 dark:bg-neutral-800 p-4 rounded-md overflow-hidden">
                <form method="POST" action="{{ route('about.update') }}" class="space-y-2">
                    <h2 class="small-title">About</h2>
                    @csrf
                    <div class="flex-grow">
                        <x-textarea class="h-auto" rows="6" id="about" class="block mt-1 w-full" type="text" name="about" placeholder="About text">
                            {!! e(auth()->user()->about) !!} </x-textarea>
                        <x-input-error :messages="$errors->get('about')" class="mt-2" />
                    </div>

                    <div class="pt-4 flex justify-between">
                        <x-secondary-button type="reset">Clear</x-secondary-button>
                        <x-primary-button class="w-48 justify-center overflow-hidden">Submit</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
