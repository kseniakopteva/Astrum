<x-starshop-layout>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="medium-title">Post Frames</h2>
            <p>Frames for your posts that will bring out the best out of your picture!</p>
        </div>
        @if (auth()->check() &&
                auth()->user()->isCreator(auth()->user()))
            <a href="{{ route('starshop.post-frames.create') }}"
                class="inline-flex items-center px-4 py-2 bg-lime-800 dark:bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-neutral-800 uppercase tracking-widest hover:bg-neutral-700 dark:hover:bg-white focus:bg-neutral-700 dark:focus:bg-white active:bg-neutral-900 dark:active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Submit
                Post Frame</a>
        @endif
    </div>
    <div class="mt-1 grid grid-cols-5 gap-4">
        @foreach ($post_frames as $frame)
            <div class="p-4 border border-neutral-200 rounded-md dark:border-neutral-700 ">
                <img class="w-full" src="{{ asset('storage/public/images/profile-picture-frames/' . $frame->image) }}"
                    alt="">
                <h3 class="small-title"><a href="">{{ $frame->name }}</a></h3>
                <p class="my-2">{{ $frame->description }}</p>
                <div class="flex justify-between items-end">
                    <span>Author: <a href="">{{ $frame->author->username }}</a></span>
                    <span>{{ $frame->price }}<i class="fa-solid fa-star"></i></span>
                </div>
            </div>
        @endforeach
    </div>
</x-starshop-layout>
