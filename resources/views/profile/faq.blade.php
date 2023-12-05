<x-profile-layout :user="$user" :followers="$followers" :following="$following">

    <section class="max-w-3xl m-auto mb-4" @if ($errors->isEmpty()) x-data="{ open: false }" @else x-data="{open:true}" @endif>
        @if (auth()->check() &&
                $user->id === auth()->user()->id &&
                !auth()->user()->isBanned())
            <x-panel :darker="true">
                <form action="{{ route('profile.index', $user->username) }}/faq" method="post">
                    @csrf
                    <header class="flex items-center cursor-pointer" x-on:click="open = ! open">
                        <img src="{{ asset('images/profile-pictures/' . auth()->user()->image) }}" alt="" width="40" height="40" class="rounded-full">
                        <h2 class="ml-4">Add new FAQ item!</h2>
                    </header>
                    <div class="mt-6" x-show="open">
                        <x-textarea class="w-full" name="question" rows="2" placeholder="Question goes here..." required></x-textarea>
                        <x-input-error class="mb-2" :messages="$errors->get('question')"></x-input-error>
                        <x-textarea class="w-full" name="answer" rows="3" placeholder="Answer goes here..." required></x-textarea>
                        <x-input-error :messages="$errors->get('answer')"></x-input-error>

                        <div class="flex justify-end">
                            <x-primary-button>Post</x-primary-button>
                        </div>
                    </div>
                </form>
            </x-panel>
        @endif
    </section>


    <section class="max-w-3xl m-auto">
        @foreach ($user->questions as $question)
            {{-- <article class="bg-white dark:bg-neutral-800 border-neutral-200 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid"> --}}
            <x-panel :item="$question" :border="false" class="relative">


                @if (auth()->check() && auth()->user()->id === $user->id)
                    <x-dropdown align="right" width="52" absolute="true">
                        <x-slot name="trigger">
                            <x-secondary-button type="submit" class="ml-2 !px-2"><i class="fa-solid fa-ellipsis"></i></x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            <form action="{{ route('faq.delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="faq_id" value="{{ $question->id }}">
                                <button onclick="return confirm('Are you sure you want to delete this?')"
                                    class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600">
                                    Delete Question
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endif


                <h2
                    class="text-lg mb-4 border-b-2 pb-4 @if (!is_null($user->colour)) border-{{ $user->colour->lightcolor }} dark:border-{{ $user->colour->darkcolor }} text-{{ $user->colour->lightcolor }} dark:text-{{ $user->colour->darkcolor }} @else border-neutral-500 @endif">
                    {{ $question->question }}</h2>
                <p>{{ $question->answer }}</p>
                {{-- </article> --}}
            </x-panel>
        @endforeach
    </section>
</x-profile-layout>
