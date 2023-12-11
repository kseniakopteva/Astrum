<x-main-layout>
    <x-page-panel>
        <h1 class="large-title mb-4">Help</h1>
        <p class="text-lg">Hello! Hopefully this page will help you to have a pleasant experience here on Astrum!</p>

        <x-help-section id="post-watermark-options">
            <x-slot name="heading">Post Watermark Options</x-slot>

            <div class="grid grid-cols-3 gap-5">
                <div>
                    <h3 class="text-lg">Center</h3>
                    <img src="{{ asset('images/watermark_center.jpg') }}" alt="">
                </div>
                <div>
                    <h3 class="text-lg">Tiled</h3>
                    <img src="{{ asset('images/watermark_tiled.jpg') }}" alt="">
                </div>
                <div>
                    <h3 class="text-lg">Bottom right</h3>
                    <img src="{{ asset('images/watermark_bottomright.jpg') }}" alt="">
                </div>
            </div>
        </x-help-section>


        <x-help-section id="money-system">
            <x-slot name="heading">Money System</x-slot>

            <div class="space-y-3">
                <p>The Astrum website uses a money currency called 'stars'. They are used to make a post, customize your profile, etc.
                    So this guide will help you understand where it is used and how much do certain things cost!</p>
                <p>First of all, how much is it to post stuff?</p>
                <ul class="list-disc ml-6 marker:text-lime-700">
                    <li>To create a <strong class="text-bold">post</strong> costs <x-price>10</x-price></li>
                    <li>To create a <strong class="text-bold">note</strong> costs <x-price>5</x-price></li>
                    <li>To create a <strong class="text-bold">post comment</strong> costs <x-price>1</x-price></li>
                    <li>To create a <strong class="text-bold">product</strong> costs <x-price>10</x-price> <span class="text-lime-600">(creators only)</span></li>
                    <li>To create a <strong class="text-bold">wallpaper</strong> costs <x-price>what price you set yourself</x-price> <span class="text-lime-600">(creators only)</span></li>
                    <li>To create a <strong class="text-bold">profile picture frame</strong> costs <x-price>what price you set yourself</x-price> <span class="text-lime-600">(creators only)</span>
                    </li>
                    <li>To create a <strong class="text-bold">post frame</strong> costs <x-price>what price you set yourself</x-price> <span class="text-lime-600">(creators only)</span></li>
                </ul>
                <p>Okay, and how do I earn these stars to create?</p>
                <ul class="list-disc ml-6 marker:text-lime-700">
                    <li>When you receive a <strong class="text-bold">like</strong> on your <strong class="text-bold">post</strong> you get <x-price>2</x-price></li>
                    <li>When you receive a <strong class="text-bold">like</strong> on your <strong class="text-bold">note</strong> you get <x-price>1</x-price></li>
                    <li>When you receive a <strong class="text-bold">like</strong> on your <strong class="text-bold">post comment</strong> you get <x-price>1</x-price></li>
                    <li>When you get a <strong class="text-bold">comment</strong> on your <strong class="text-bold">post</strong> you get <x-price>3</x-price></li>
                    <li>When you get a <strong class="text-bold">comment</strong> on your <strong class="text-bold">note</strong> you get <x-price>2</x-price></li>
                    <li>When you get a <strong class="text-bold">follower</strong> you get <x-price>3</x-price></li>
                    <li>When someone <strong class="text-bold">buys your product</strong> and you <strong class="text-bold">fulfill their order</strong>, you get
                        <x-price>the amount you set as a price</x-price> (if the product's currency was set as stars) or
                        <x-price>100</x-price> (if product's currency was euro) <span class="text-lime-600">(creators only)</span>
                    </li>

                    <li>When someone <strong class="text-bold">likes</strong> your <strong class="text-bold"><a href="{{ route('starshop') }}" class="underline">Starshop</a> submission</strong>
                        (wallpaper, profile
                        picture frame, or post frame) you get
                        <x-price>2</x-price> <span class="text-lime-600">(creators only)</span>
                    </li>
                    <li>When someone <strong class="text-bold">buys</strong> your <strong class="text-bold"><a href="{{ route('starshop') }}" class="underline">Starshop</a> submission</strong>
                        (wallpaper, profile
                        picture frame, or post frame) you get
                        <x-price>the amount you set as a price</x-price> <span class="text-lime-600">(creators only)</span>
                    </li>
                </ul>
                <p>And finally, you become a <span class="text-lime-600">creator</span> whe you reach <strong class="text-bold">30 followers</strong>!</p>
            </div>
        </x-help-section>

        <x-help-section id="post-frame-upload-guidelines">
            <x-slot name="heading">Post Frame Upload Guidelines</x-slot>

            <p>Let's assume you want to upload this post frame:</p>
            <img src="{{ asset('images/pf_guide_1.png') }}" alt="" class="w-28 h-28 m-8">
            <h3 class="text-lg my-2 font-bold">Percentage</h3>
            <p>We count the parts of the frame according to this picture:</p>
            <img id="img" src="{{ asset('images/pf_guide_2.png') }}" alt="" class="w-72 my-3">
            <p>And so the 'Percentage' will be 12.5. The width is how large it will be:</p>
            <h3 class="text-lg my-2 font-bold">Width</h3>
            <p class="mb-3">First is Width: 10, the second 20.</p>
            <div class="grid grid-cols-2 gap-10">
                @php
                    $first_example = new stdClass();
                    $first_example->image = '../pf_guide_1.png';
                    $first_example->percentage = '12.5';
                    $first_example->width = '10';

                    $sec_example = new stdClass();
                    $sec_example->image = '../pf_guide_1.png';
                    $sec_example->percentage = '12.5';
                    $sec_example->width = '20';
                @endphp
                <div class="m-5">
                    <x-dummy-post :post_frame="$first_example" :col_start_2="false" />
                </div>
                <div class="m-5">
                    <x-dummy-post :post_frame="$sec_example" :col_start_2="false" />
                </div>
            </div>
        </x-help-section>

    </x-page-panel>
</x-main-layout>

@push('scripts')
    <script>
        (changeImg = function() {
            if (localStorage.getItem("theme") === 'dark') {
                document.querySelector('#img').src = '{{ asset('images/pf_guide_2_dark.png') }}'
            }
        })()
    </script>
@endpush
