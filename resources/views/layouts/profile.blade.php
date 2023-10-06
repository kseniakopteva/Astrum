@props(['user', 'posts', 'followers', 'following'])

<x-main-layout>
    <div class="max-w-7xl m-auto px-4">
        <header class="mb-10 p-8 pb-4 bg-white rounded-b-md {{-- bg-white/50 dark:bg-neutral-800/50 backdrop-blur-sm --}}  max-w-4xl m-auto ">
            <div class="grid grid-cols-5">

                {{-- Profile Image --}}
                <div
                    class=" col-span-2 sm:col-span-1 order-1 sm:order-1 profile-image-container mr-10 w-32 h-32 sm:w-full sm:h-auto">
                    <img class="profile-image rounded-full shadow-md w-full h-auto" src="/images/{{ $user->image }}"
                        alt="" class="profile-picture" width="100" height="100"
                        style=" pointer-events: none; user-select: none;">
                    {{-- <img class="profile-image-overlay" src="/images/profile_border.png" alt=""> --}}

                </div>
                {{-- Personal Info Section --}}
                <section class="order-3  sm:order-2 col-span-5 sm:col-span-3 mt-4 sm:mt-0 sm:ml-6">
                    <div class="flex space-x-4 items-center">
                        @if ($user->name)
                            <h1 class="large-title">{{ $user->name }}</h1>
                        @else
                            <h1 class="large-title">{{ $user->username }}</h1>
                        @endif

                        @if ($user->isCreatorOrMore($user))
                            <span
                                class="rounded-md py-1 px-2 bg-{{ $user->badge->lightcolor }} dark:bg-{{ $user->badge->darkcolor }} dark:text-neutral-400">
                                {{ ucfirst($user->badge->name) }}</span>
                        @endif

                    </div>


                    @if ($user->name)
                        <h2 class="small-title inline-block mr-2">{{ $user->username }}</h2>
                    @endif

                    @if ($user->isAdmin($user))
                        <span class="small-title text-red-500"><?php echo strtoupper('Admin'); ?></span>
                    @elseif ($user->isMod($user))
                        <span class="small-title text-red-500"><?php echo strtoupper('Moderator'); ?></span>
                    @endif

                    @if ($user->bio && strlen($user->bio) >= 150)
                        <div class="max-w-md mt-4 pr-4" x-data="{ open: false, maxLength: 150, fullText: '', slicedText: '' }" x-init="fullText = $el.firstElementChild.textContent.trim();
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
                </section>
                {{-- Following & Button Section --}}
                <section
                    class="order-2 sm:order-3 mt-4 sm:mt-0 col-span-3 sm:col-span-1 flex flex-col
                     w-full items-center">
                    <div class="flex gap-8 items-center mb-2 justify-between">
                        @auth
                            @if ($user !== auth()->user())
                                <div class="flex">
                                    @if (!auth()->user()->isFollowing($user))
                                        <form method="POST" action="{{ route('user.follow') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <x-primary-button type="submit">Follow</x-primary-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('user.unfollow') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <x-secondary-button type="submit"
                                                class="bg-white/30 hover:border-neutral-300">Unfollow</x-secondary-button>
                                        </form>
                                    @endif


                                    <x-dropdown align="right" width="52">
                                        <x-slot name="trigger">
                                            <x-secondary-button type="submit" class="ml-2 !px-2"><i
                                                    class="fa-solid fa-ellipsis"></i></x-secondary-button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'report')"
                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                                {{ __('Report') }}
                                            </button>
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'block')"
                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                                {{ __('Block') }}
                                            </button>
                                            @if (in_array(auth()->user()->role, ['mod', 'admin']))
                                                <button x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'ban')"
                                                    class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-neutral-700">
                                                    {{ __('Ban User') }}
                                                </button>
                                                @admin
                                                    @if ($user->role == 'user')
                                                        <form method="POST" action="{{ route('make.creator') }}">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                                            <button type="submit"
                                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-green-600">
                                                                Make Creator</button>
                                                        </form>
                                                    @elseif ($user->isCreator($user))
                                                        <form method="POST" action="{{ route('make.mod') }}">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                                            <button type="submit"
                                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-green-600">
                                                                Make Moderator</button>
                                                        </form>
                                                    @elseif ($user->isMod($user))
                                                        <form method="POST" action="{{ route('remove.mod') }}">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                                            <button type="submit"
                                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-green-600">
                                                                Remove Moderator</button>
                                                        </form>
                                                        {{-- <form method="POST" action="{{ route('make.admin') }}">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                                            <button type="submit"
                                                                class="block w-full px-4 py-2 text-left text-sm dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-green-600">
                                                                Make Admin</button>
                                                        </form> --}}
                                                    @endif
                                                @endadmin
                                            @endif
                                        </x-slot>
                                    </x-dropdown>



                                </div>
                            @else
                                <a href="/settings"
                                    class="whitespace-nowrap inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    Edit profile
                                </a>
                            @endif
                        @endauth
                    </div>
                    <div class="flex space-x-4 items-start whitespace-nowrap justify-end mt-2">
                        <button @if ($user->followers->count() == 0) style="pointer-events: none" @endif
                            class="hover:text-lime-700 flex flex-col items-center" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'open-followers')">
                            <span class="flex flex-col items-center"><span
                                    class="font-black">{{ $user->followers->count() }}</span>
                                Followers</span>
                        </button>
                        <button @if ($user->following->count() == 0) style="pointer-events: none" @endif
                            class="hover:text-lime-700 flex flex-col items-center" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'open-following')">
                            <span class="flex flex-col items-center"><span
                                    class="font-black">{{ $user->following->count() }}</span>
                                Following</span>
                        </button>

                    </div>
                </section>

            </div>
            {{-- Links Section --}}
            <section class="mt-6 flex justify-center pt-3 border-t border-neutral-300">
                <nav class="profile-nav">
                    <ul class="flex flex-wrap justify-center gap-1 sm:gap-3 lg:gap-6">
                        <li><a href="/u/{{ $user->username }}">Home</a></li>
                        <li><a href="/u/{{ $user->username }}/posts">Posts</a></li>
                        <li><a href="/u/{{ $user->username }}/notes">Notes</a></li>
                        @if ($user->isCreatorOrMore($user))
                            <li><a href="/u/{{ $user->username }}/shop">Shop</a></li>
                            <li><a href="/u/{{ $user->username }}/faq">FAQ</a></li>
                            <li><a href="/u/{{ $user->username }}/about">About</a></li>
                        @endif
                    </ul>
                </nav>
            </section>
        </header>
        {{ $slot }}
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
    <x-user-ban-modal :user="$user"></x-user-ban-modal>
    <x-user-block-modal :user="$user"></x-user-block-modal>
    <x-user-report-modal :user="$user"></x-user-report-modal>

</x-main-layout>
