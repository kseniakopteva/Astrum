<x-main-layout>
    <div class="max-w-7xl m-auto mt-16 p-4">
        <header
            class="flex flex-col sm:flex-row mt-8 mb-10 pb-8 bg-white/50 dark:bg-neutral-800/50 rounded-md backdrop-blur-sm">
            {{-- <img class="rounded-full w-32 h-32 sm:w-44 sm:h-44" src="https://i.pravatar.cc/100?u={{ $user->id }}" --}}
            <img class="-mt-16 ml-8 rounded-full w-32 h-32 sm:w-44 sm:h-44 shadow-md" src="/images/{{ $user->image }}"
                alt="" class="profile-picture" width="100" height="100"
                style=" pointer-events: none; user-select: none;">
            <div class="ml-12 mt-4">

                <div class="flex gap-8">

                    <h1 class="large-title">{{ $user->username }}</h1>
                    @if (auth()->check() && $user !== auth()->user())
                        @if (!auth()->user()->isFollowing($user))
                            <form method="POST" action="{{ route('user.follow') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <x-secondary-button type="submit">Follow</x-secondary-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('user.unfollow') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <x-secondary-button type="submit">Unfollow</x-secondary-button>
                            </form>
                        @endif

                    @endif

                </div>

                @if ($user->name)
                    <h2 class="small-title inline-block mr-2">{{ $user->name }}</h2>
                @endif
                <span
                    class="rounded-md py-1 px-2 bg-{{ $user->badge->lightcolor }} dark:bg-{{ $user->badge->darkcolor }} dark:text-neutral-400">
                    {{ ucfirst($user->badge->name) }}</span>

                @if ($user->bio && strlen($user->bio) >= 150)
                    <div class="max-w-lg mt-4 pr-4" x-data="{ open: false, maxLength: 150, fullText: '', slicedText: '' }" x-init="fullText = $el.firstElementChild.textContent.trim();
                    slicedText = fullText.slice(0, maxLength) + '...'">
                        <div class="inline" x-text="open ? fullText : slicedText" x-transition>
                            {{ $user->bio }}
                        </div>
                        <button class="text-lime-500" @click="open = ! open"
                            x-text="open ? 'Show less' : 'Show more'"></button>
                    </div>
                @else
                    <div class="max-w-lg mt-4">
                        {{ $user->bio }}
                    </div>
                @endif

            </div>
        </header>
        <div class="grid grid-cols-3 gap-8">
            <div class="col-span-3 md:col-span-2">
                <div class="flex">
                    <h2 class="medium-title mb-4 text-white">Posts</h2>
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
                                        <x-text-input id="alt" class="block mt-1 w-full" type="text"
                                            name="alt" placeholder="Alt" value="{{ old('alt') }}" />
                                        <x-input-error :messages="$errors->get('alt')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <x-textarea class="h-auto" rows="4" id="body" class="block mt-1 w-full"
                                        type="text" name="body" placeholder="Post text"
                                        value="{{ old('body') }}" />
                                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                                </div>
                                <div class="mt-1 flex justify-between">
                                    <x-secondary-button type="reset"
                                        onclick="deletePreview()">Clear</x-secondary-button>
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
                                    type="text" name="notebody" placeholder="Note text"
                                    value="{{ old('notebody') }}" required />
                                <x-input-error :messages="$errors->get('notebody')" class="mt-2" />
                            </div>
                            <div class="mt-1 flex justify-between">
                                <x-secondary-button type="reset">Clear</x-secondary-button>
                                <x-primary-button
                                    class="mt-1 w-48 justify-center overflow-hidden">Submit</x-primary-button>
                            </div>
                        </form>
                    </div>
            </div>
            @endif
        </div>
        <div class="space-y-4">
            @foreach ($user->notes()->latest()->get()->take(20) as $note)
                <x-feed-note :note=$note></x-feed-note>
            @endforeach
            @if ($user->notes->count() > 20)
                <x-secondary-button>View all notes</x-secondary-button>
            @endif
        </div>
    </div>
    </div>
    </div>
    <script>
        function deletePreview() {
            let preview = document.querySelector('#imagePreview');

            if (preview.hasAttribute('src')) {
                preview.removeAttribute('src');
            } else {
                place.style.display = 'none';
                preview.removeAttribute('src');
            }
        }
    </script>
</x-main-layout>
