<x-starshop-layout>
    <div class="max-w-xl m-auto mb-6">
        <h2 class="medium-title mb-6">Submit a Post Frame</h2>
        <form action="{{ route('starshop.post-frames.store') }}" method="POST" class="space-y-2" enctype="multipart/form-data">
            @csrf
            <div>
                <x-input-label for="name">Name</x-input-label>
                <x-text-input id="name" name="name" class="w-full" required value="{{ old('name') }}"></x-text-input>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <x-image-upload class="h-80 w-full"></x-image-upload>
            <x-input-error :messages="$errors->get('image')" class="mt-2" />

            <div>
                <x-input-label for="width">Width</x-input-label>
                <div class="flex items-center gap-5">
                    <input type="number" name="width" id="width" value="{{ old('width') }}"
                        class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <a target="_blank" href="{{ route('help') . '#post-frame-upload-guidelines' }}" class="question-icon-link">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.4em"
                            viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                        </svg>
                    </a>
                </div>
                <x-input-error :messages="$errors->get('width')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="percentage">Percentage of image for frame</x-input-label>
                <div class="flex items-center gap-5">
                    <input type="number" step="0.01" name="percentage" id="percentage" value="{{ old('percentage') }}"
                        class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <a target="_blank" href="{{ route('help') . '#post-frame-upload-guidelines' }}" class="question-icon-link">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.4em"
                            viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                        </svg>
                    </a>
                </div>
                <x-input-error :messages="$errors->get('percentage')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description">Description</x-input-label>
                <x-textarea id="description" name="description" class="w-full" required>{{ old('price') }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="price">Price</x-input-label>
                <div class="flex items-center gap-4">
                    <input type="number" name="price" id="price" value="{{ old('price') }}"
                        class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <span class="text-red-600 dark:text-red-300">
                        <x-price>You will have to pay this price to post</x-price>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            {{-- <div class="pb-2">
                <x-input-label for="tags">Tags</x-input-label>
                <x-text-input class="h-auto" id="tags" class="block w-full" type="text" name="tags" placeholder="Tags (separate with comma)" value="{{ old('tags') }}" />
                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
            </div> --}}

            <x-primary-button>Submit</x-primary-button>
        </form>
    </div>
</x-starshop-layout>
