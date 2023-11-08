<x-profile-layout :user="$user" :followers="$followers" :following="$following">

    <section class="max-w-3xl m-auto mb-4"
        @if ($errors->isEmpty()) x-data="{ open: false }" @else x-data="{open:true}" @endif>
        @if (auth()->check() &&
                $user->id === auth()->user()->id &&
                !auth()->user()->isBanned(auth()->user()))
            <form action="/u/{{ $user->id }}/faq" method="post"
                class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-900 dark:border-neutral-700 p-4 rounded-md">
                @csrf
                <header class="flex items-center cursor-pointer" x-on:click="open = ! open">
                    <img src="{{ asset('storage/images/profile-pictures/' . auth()->user()->image) }}" alt=""
                        width="40" height="40" class="rounded-full">
                    <h2 class="ml-4">Add new FAQ item!</h2>
                </header>
                <div class="mt-6" x-show="open">
                    <x-textarea class="w-full" name="question" rows="2" placeholder="Question goes here..."
                        required></x-textarea>
                    <x-input-error class="mb-2" :messages="$errors->get('question')"></x-input-error>
                    <x-textarea class="w-full" name="answer" rows="3" placeholder="Answer goes here..."
                        required></x-textarea>
                    <x-input-error :messages="$errors->get('answer')"></x-input-error>

                    <div class="flex justify-end"><x-primary-button>Post</x-primary-button></div>
                </div>
            </form>
        @endif
    </section>


    <section class="max-w-3xl m-auto mb-8">
        @foreach ($user->questions as $question)
            <article
                class="bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid">
                <h2 class="text-lg mb-4 border-b-2 pb-4 border-neutral-500">{{ $question->question }}</h2>
                <p>{{ $question->answer }}</p>
            </article>
        @endforeach
    </section>
</x-profile-layout>
