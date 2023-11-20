<x-profile-layout :user="$user" :followers="$followers" :following="$following">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 xl:gap-10 max-w-5xl mx-auto">
        <div
            class="lg:col-span-2 bg-white dark:bg-neutral-800 p-4 pt-4 mb-4 break-inside-avoid border-l-4
        @if (!is_null($user->colour)) border-{{ $user->colour->lightcolor }} dark:border-{{ $user->colour->darkcolor }} @else border-lime-600 dark:border-lime-700 @endif">
            <div class="flex items-center mb-4 gap-3">
                <h2 class="medium-title">About Me</h2>

                @if (auth()->check() && $user->id == auth()->user()->id)
                    <x-change-about-modal />
                @endif
            </div>
            @if (!is_null($user->about))
                <p>{!! nl2br(e($user->about)) !!}</p>
            @else
                <p class="text-sm italic text-neutral-400 dark:text-neutral-500 m-2 mt-6">Nothing here yet.</p>
            @endif
        </div>
        <div
            class="bg-white 200 dark:bg-neutral-800 p-4 pt-4 mb-4 break-inside-avoid border-l-4
        @if (!is_null($user->colour)) border-{{ $user->colour->lightcolor }} dark:border-{{ $user->colour->darkcolor }} @else border-lime-600 dark:border-lime-700 @endif">
            <div class="flex items-center mb-2 gap-3">
                <h2 class="medium-title">Social Links</h2>

                @if (auth()->check() && $user->id == auth()->user()->id)
                    <x-new-link-modal />
                @endif
            </div>

            @if (!$user->aboutLinks->isEmpty())
                <div class="space-y-2">
                    @foreach ($user->aboutLinks as $link)
                        <div class="flex justify-between w-full items-center">
                            <a class="text-lg underline
                    @if (!is_null($user->colour)) text-{{ $user->colour->lightcolor }} dark:text-{{ $user->colour->darkcolor }} @endif "
                                href="{{ $link->link }}">{{ $link->name }}</a>

                            @if (auth()->check() && auth()->user()->id == $user->id)
                                <form method="POST" action="{{ route('about.link.destroy') }}">
                                    @csrf
                                    <input type="hidden" name="link_id" value="{{ $link->id }}">
                                    <button class="text-sm text-neutral-500" onclick="return confirm('Are you sure you want to remove this link?')">Remove</button>
                                </form>
                            @endif

                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm italic text-neutral-400 dark:text-neutral-500 m-2 mt-6">Nothing here yet.</p>
            @endif
        </div>
    </div>
</x-profile-layout>
