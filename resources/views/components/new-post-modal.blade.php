@if (
    $errors->getBag('default')->has('body') ||
        $errors->getBag('default')->has('title') ||
        $errors->getBag('default')->has('image') ||
        $errors->getBag('default')->has('alt'))
    <div x-data="{ show: true }"> {{-- do not remove, stops working --}}
        <?php $show = true; ?>
    @else
        <div x-data="{ show: false }"> {{-- do not remove, stops working --}}
            <?php $show = false; ?>
@endif
<x-secondary-button class="ml-4" x-on:click.prevent="$dispatch('open-modal', 'open-new-post')">New
    Post</x-secondary-button>
{{-- <x-secondary-button class="ml-4" x-on:click="open = ! open">New Post</x-secondary-button> --}}

<x-modal name="open-new-post" :show="$show" focusable>
    <div class="shadow-lg bg-neutral-100 dark:bg-neutral-800 p-4 overflow-hidden">
        <div class="flex justify-between items-center">
            <h2 class="small-title">New Post</h2>
            <span class="text-red-600 dark:text-red-300">
                Cost: <x-price>10</x-price>
            </span>
        </div>
        <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <div>
                <x-text-input id="title" class="block w-full" type="text" name="title" placeholder="Title"
                    required value="{{ old('title') }}" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            <div>
                <x-input-label>Image</x-input-label>
                <x-image-upload class="mr-1 h-80 w-full"></x-image-upload>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
            <div>
                <x-text-input id="alt" class="block w-full" type="text" name="alt" placeholder="Alt"
                    value="{{ old('alt') }}" />
                <x-input-error :messages="$errors->get('alt')" class="mt-2" />
            </div>
            <div class="flex-grow">
                <x-textarea class="h-auto" rows="4" id="body" class="block w-full" type="text"
                    name="body" placeholder="Post text" value="{{ old('body') }}" />
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>
            <div class="flex-grow">
                <x-text-input class="h-auto" id="tags" class="block w-full" type="text" name="tags"
                    placeholder="Tags (separate with comma)" value="{{ old('tags') }}" />
                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
            </div>
            <div>
                <x-input-label class="pt-2">Post Frame</x-input-label>
                @if (auth()->user()->ownedPostFrames->isEmpty())
                    <span class="inline-block mt-1 italic text-neutral-500 dark:text-neutral-400">You don't have any
                        post frames yet.
                        Visit <a href="{{ route('starshop') }}" class="underline">Starshop</a>!</span>
                @else
                    <div class="grid grid-cols-4 md:grid-cols-5 mt-1 gap-5">
                        <div
                            class="border bg-white dark:bg-neutral-900 border-neutral-300 dark:border-neutral-700 rounded-md p-3 relative">
                            <div class="w-full h-20 md:h-24 mb-4 grid place-content-center">
                                <span class="text-xl">x</span>
                            </div>
                            None

                            <div class="absolute top-0 left-0 w-full h-full cursor-default rounded-md"
                                onclick="document.querySelector('{{ '#none' }}').checked = !document.querySelector('{{ '#none' }}').checked">
                            </div>
                            <input
                                class="absolute bottom-2 right-2 border-2 border-lime-600 bg-lime-200 checked:bg-lime-700 rounded-full p-2"
                                type="radio" checked name="frame" id="none" value="none" required>
                        </div>
                        @foreach (auth()->user()->ownedPostFrames as $frame)
                            <div class="border border-neutral-300 rounded-md p-3 relative">
                                <div class="w-full h-20 md:h-24 mb-4"
                                    style="border-image: url('{{ asset('storage/images/post-frames/' . $frame->image) }}') {{ $frame->percentage }}% round;
                    border-style: solid; border-width: {{ $frame->width }}px !important;">
                                </div>
                                {{ $frame->name }}

                                <div class="absolute top-0 left-0 w-full h-full cursor-default rounded-md"
                                    onclick="document.querySelector('{{ '#frame-' . $frame->id }}').checked = !document.querySelector('{{ '#frame-' . $frame->id }}').checked">
                                </div>
                                <input
                                    class="absolute bottom-2 right-2 border-2 border-lime-600 bg-lime-200 checked:bg-lime-700 rounded-full p-2"
                                    type="radio" name="frame" id="{{ 'frame-' . $frame->id }}"
                                    value="{{ $frame->id }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="pt-4 flex justify-between">
                <x-secondary-button type="reset" onclick="deletePreview()">Clear</x-secondary-button>
                <x-primary-button class="w-48 justify-center overflow-hidden">Submit</x-primary-button>
            </div>
        </form>
    </div>
    </div>
</x-modal>
