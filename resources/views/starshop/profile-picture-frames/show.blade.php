<x-starshop-product-layout :item="$profile_picture_frame" type="profile-picture-frame">
    <div class="border-b border-neutral-200 dark:border-neutral-700 pb-16 grid grid-cols-4">
        <img class="h-[calc(100vh-40rem)] m-auto col-span-3"
            src="{{ asset('images/profile-picture-frames/' . $profile_picture_frame->image) }}" alt="">
        {{-- Profile Image --}}
        <div
            class="text-center  bg-neutral-300 dark:bg-neutral-900 pt-4 pb-8

        flex justify-center flex-col items-center rounded-lg">
            <h3 class="mb-4 small-title">Preview</h3>
            <div class="profile-image-container w-24 h-24 sm:w-1/2 sm:h-auto">
                <img class="profile-image rounded-full shadow-md h-full w-full"
                    src="{{ asset('images/profile-pictures/' . auth()->user()->image) }}" alt=""
                    class="profile-picture" width="100" height="100"
                    style=" pointer-events: none; user-select: none;">
                <img class="profile-image-overlay"
                    src="{{ asset('images/profile-picture-frames/' . $profile_picture_frame->image) }}">
            </div>
        </div>
    </div>
</x-starshop-product-layout>
