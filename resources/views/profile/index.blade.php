<x-profile-layout :user="$user" :posts="$posts" :followers="$followers" :following="$following">
    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-3 md:col-span-2">
            <div class="flex">
                <h2 class="medium-title mb-4 text-white">Recent Posts</h2>
                @if (auth()->id() === $user->id)
                    @php
                        // $errors = collect($errors->getMessages())->except(['notebody']);
                    @endphp
                    @if (
                        $errors->getBag('default')->has('body') ||
                            $errors->getBag('default')->has('title') ||
                            $errors->getBag('default')->has('image') ||
                            $errors->getBag('default')->has('alt'))
                        <div x-data="{ open: true }">
                        @else
                            <div x-data="{ open: false }">
                    @endif

                    <x-secondary-button class="ml-4" x-on:click="open = ! open">New Post</x-secondary-button>

                    <div class="fixed top-0 right-1/4 w-1/2 mt-4 shadow-lg border bg-neutral-100 border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 p-4 rounded-md overflow-hidden"
                        @click.away="open = false" style="display:none" x-show="open"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                    placeholder="Title" required value="{{ old('title') }}" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div class="">
                                <div>
                                    <x-image-upload class="mr-1 h-80 w-full"></x-image-upload>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input id="alt" class="block mt-1 w-full" type="text" name="alt"
                                        placeholder="Alt" value="{{ old('alt') }}" />
                                    <x-input-error :messages="$errors->get('alt')" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex-grow">
                                <x-textarea class="h-auto" rows="4" id="body" class="block mt-1 w-full"
                                    type="text" name="body" placeholder="Post text" value="{{ old('body') }}" />
                                <x-input-error :messages="$errors->get('body')" class="mt-2" />
                            </div>
                            <div class="mt-1 flex justify-between">
                                <x-secondary-button type="reset" onclick="deletePreview()">Clear</x-secondary-button>
                                <x-primary-button
                                    class="mt-1 w-48 justify-center overflow-hidden">Submit</x-primary-button>
                            </div>
                        </form>
                    </div>
            </div>
            @endif
        </div>

        {{-- <div class="grid grid-cols-2 gap-4"> --}}
        <div class="columns lg:columns-2 overflow-hidden">
            @foreach ($posts as $post)
                <x-feed-post class="cols-1" :post=$post></x-feed-post>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
    <div class="hidden md:block">
        <div class="flex">
            <h2 class="medium-title mb-4 text-white">Notes</h2>
            @if (auth()->id() === $user->id)
                @if ($errors->getBag('default')->has('notebody'))
                    <div x-data="{ open: true }">
                    @else
                        <div x-data="{ open: false }">
                @endif

                <x-secondary-button class="ml-4" x-on:click="open = ! open">New Note</x-secondary-button>

                <div class="fixed top-0 right-1/4 w-1/2 mt-4 shadow-lg border bg-neutral-100 border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 p-4 rounded-md overflow-hidden"
                    @click.away="open = false" style="display:none" x-show="open"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <form method="POST" action="{{ route('note.store') }}">
                        @csrf
                        <div class="flex-grow">
                            <x-textarea class="h-auto" rows="6" id="notebody" class="block mt-1 w-full"
                                type="text" name="notebody" placeholder="Note text" value="{{ old('notebody') }}"
                                required />
                            <x-input-error :messages="$errors->get('notebody')" class="mt-2" />
                        </div>
                        <div class="mt-1 flex justify-between">
                            <x-secondary-button type="reset">Clear</x-secondary-button>
                            <x-primary-button class="mt-1 w-48 justify-center overflow-hidden">Submit</x-primary-button>
                        </div>
                    </form>
                </div>
        </div>
        @endif
    </div>
    <div class="space-y-4">
        @foreach ($user->notes()->latest()->get()->take(20) as $note)
            <x-feed-note :note=$note :user=$user></x-feed-note>
        @endforeach
        @if ($user->notes->count() > 20)
            <x-secondary-button>View all notes</x-secondary-button>
        @endif
    </div>
    </div>
</x-profile-layout>
