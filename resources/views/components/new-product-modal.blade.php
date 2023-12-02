@if (
    $errors->getBag('default')->has('name') ||
        $errors->getBag('default')->has('image') ||
        $errors->getBag('default')->has('type') ||
        $errors->getBag('default')->has('max_slots') ||
        $errors->getBag('default')->has('description') ||
        $errors->getBag('default')->has('tags') ||
        $errors->getBag('default')->has('price'))
    <div x-data="{ show: true }"> {{-- do not remove, stops working --}}
        <?php $show = true; ?>
    @else
        <div x-data="{ show: false }"> {{-- do not remove, stops working --}}
            <?php $show = false; ?>
@endif
<div class="flex justify-end mb-4 -mt-2">
    <x-secondary-button class="" x-on:click.prevent="$dispatch('open-modal', 'open-new-product')">New
        Product</x-secondary-button>
</div>
{{-- <x-secondary-button class="ml-4" x-on:click="open = ! open">New Post</x-secondary-button> --}}

<x-modal name="open-new-product" :show="$show" focusable>
    <div class="shadow-lg bg-neutral-100 dark:bg-neutral-800 p-4 overflow-hidden">
        <div class="flex justify-between items-center">
            <h2 class="small-title">New Product</h2>
            <span class="text-red-600 dark:text-red-300">
                Cost: <x-price>10</x-price>
            </span>
        </div>
        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <div>
                <x-text-input id="name" class="block w-full" type="text" name="name" placeholder="Name" required value="{{ old('name') }}" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label>Image</x-input-label>
                <x-image-upload class="mr-1 h-80 w-full"></x-image-upload>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <x-input-label class="!inline-block" for="type">Product Type:</x-input-label>
                    <select name="type" id="type" onchange="checkSelectedType(this)"
                        class="cursor-pointer flex-grow my-2 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                        <option value="unlimited" selected="selected">Unlimited purchases</option>
                        <option value="one-time">One-time purchase</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>
            <div id="slots" style="display: block">
                <div class="flex items-center gap-3">
                    <x-input-label for="max_slots">Number of Slots:</x-input-label>
                    <input type="number" name="max_slots" id="max_slots" value="{{ old('max_slots') }}"
                        class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <x-input-error :messages="$errors->get('max_slots')" class="mt-2" />
                    <span class="text-sm">(Leave blank for infinite slots)</span>
                </div>
            </div>
            <div class="flex-grow">
                <x-textarea class="h-auto" required rows="4" id="description" class="block w-full" type="text" name="description" placeholder="Description" value="{{ old('description') }}" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            {{-- <div class="flex-grow">
                <x-text-input class="h-auto" id="tags" class="block w-full" type="text" name="tags" placeholder="Tags (separate with comma)" value="{{ old('tags') }}" />
                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
            </div> --}}

            <div>
                <div class="flex items-center gap-3">
                    <x-input-label for="price">Price: </x-input-label>
                    <input type="number" name="price" required id="price" value="{{ old('price') }}"
                        class="block border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />

                    <x-input-label class="!inline-block" for="currency">Currency:</x-input-label>
                    <select name="currency" id="currency"
                        class="cursor-pointer my-2 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                        <option value="stars" selected="selected">Stars ★</option>
                        <option value="euro">Euro €</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-between">
                <x-secondary-button type="reset" onclick="deletePreview()">Clear</x-secondary-button>
                <x-primary-button class="w-48 justify-center overflow-hidden">Submit</x-primary-button>
            </div>
        </form>
    </div>
    </div>
</x-modal>

@push('scripts')
    <script>
        function checkSelectedType(that) {
            if (that.value == "unlimited") {
                document.getElementById("slots").style.display = "block";
            } else {
                document.getElementById("slots").style.display = "none";
            }
        }
    </script>
@endpush
