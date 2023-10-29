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
            <div class="pt-4 flex justify-between">
                <x-secondary-button type="reset" onclick="deletePreview()">Clear</x-secondary-button>
                <x-primary-button class="w-48 justify-center overflow-hidden">Submit</x-primary-button>
            </div>
        </form>
    </div>
    </div>
</x-modal>
