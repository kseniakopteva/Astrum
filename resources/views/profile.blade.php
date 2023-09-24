<x-main-layout>
    <div class="mt-16 p-4">
        <header
            class=" mt-4 mb-10 px-8 pb-8 bg-white/50 dark:bg-neutral-800/50 rounded-md backdrop-blur-sm max-w-7xl m-auto ">
            <div class="flex flex-col sm:flex-row">
                <img class="-mt-12 mr-12 rounded-full w-32 h-32 sm:w-44 sm:h-44 shadow-md"
                    src="/images/{{ $user->image }}" alt="" class="profile-picture" width="100" height="100"
                    style=" pointer-events: none; user-select: none;">
                <div class="mt-4 flex-grow {{-- flex flex-col sm:flex-row items-start --}} grid grid-cols-6">
                    <div class="flex-grow col-span-6 md:col-span-5">
                        <h1 class="large-title">{{ $user->username }}</h1>

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
                    <div
                        class="mt-4 md:mt-0 col-span-6 md:col-span-1 flex flex-row md:flex-col justify-between md:justify-start w-full items-baseline md:items-end">
                        <div class="flex gap-8 items-center mb-2 justify-between">
                            @if (auth()->check() && $user !== auth()->user())
                                <div class="flex">
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
                                            <x-secondary-button type="submit"
                                                class="bg-white/30 hover:border-neutral-300">Unfollow</x-secondary-button>
                                        </form>
                                    @endif
                                    <x-secondary-button type="submit" class="ml-2"><i
                                            class="fa-regular fa-envelope"></i></x-secondary-button>
                                </div>
                            @else
                                <a href="/settings"
                                    class="whitespace-nowrap inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    Edit profile
                                </a>
                            @endif
                        </div>
                        <div
                            class="flex md:flex-col space-x-2 md:space-x-0 sm:items-start whitespace-nowrap justify-end mt-2 md:space-y-2">
                            <button class="hover:text-lime-700" x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'open-followers')">
                                <span class="font-black">{{ $user->followers->count() }}</span>
                                Followers
                            </button>
                            <button class="hover:text-lime-700" x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'open-following')">
                                <span class="font-black">{{ $user->following->count() }}</span>
                                Following
                            </button>

                        </div>
                    </div>

                </div>
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
    <x-follow-modal name="followers" :users="$followers"></x-follow-modal>
    <x-follow-modal name="following" :users="$following"></x-follow-modal>

</x-main-layout>
