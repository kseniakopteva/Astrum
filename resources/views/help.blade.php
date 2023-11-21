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
