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
                <div class="flex items-center gap-3">
                    <x-input-label class="!inline-block" for="watermark">Image Watermark:</x-input-label>
                    <select name="watermark" id="watermark"
                        class="cursor-pointer flex-grow my-2 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                        <option value="none" selected="selected">(None)</option>
                        <option value="center">Center</option>
                        <option value="bottom-right">Bottom right</option>
                        <option value="tiled">Tiled</option>
                    </select>
                    <a target="_blank" href="{{ route('help') . '#post-watermark-options' }}" class="question-icon-link">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.4em"
                            viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                        </svg>
                    </a>
                    <x-input-label class="!inline-block ml-3" for="watermark-color">Color:</x-input-label>
                    <input class="color-input w-16 self-stretch cursor-pointer rounded-md my-1" type="color"
                        name="watermark_color" value="#eeeeee">
                </div>
                <x-input-error :messages="$errors->get('watermark')" class="mt-2" />
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
                                    style="border-image: url('{{ asset('images/post-frames/' . $frame->image) }}') {{ $frame->percentage }}% round;
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
