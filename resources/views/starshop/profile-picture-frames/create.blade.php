<x-starshop-layout>
    <div class="max-w-xl m-auto">
        <h2 class="medium-title mb-6">Submit a Profile Picture Frame</h2>
        <form action="{{ route('starshop.profile-picture-frames.store') }}" method="POST" class="space-y-2"
            enctype="multipart/form-data">
            @csrf
            <div>
                <x-input-label for="name">Name</x-input-label>
                <x-text-input id="name" name="name" class="w-full" value="{{ old('name') }}"
                    required></x-text-input>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <x-image-upload class="h-80 w-full"></x-image-upload>
            <x-input-error :messages="$errors->get('image')" class="mt-2" />

            <div>
                <x-input-label for="description">Description</x-input-label>
                <x-textarea id="description" name="description" class="w-full" required>{{ old('image') }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="price">Price</x-input-label>
                <input type="number" name="price" id="price" value="{{ old('price') }}"
                    class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <br>
            Tags here
            {{-- TODO: Tags --}}
            <br>

            <x-primary-button>Submit</x-primary-button>
        </form>
    </div>
</x-starshop-layout>
