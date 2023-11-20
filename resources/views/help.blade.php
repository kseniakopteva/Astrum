<x-main-layout>
    <div class="bg-white dark:bg-neutral-800 max-w-7xl m-auto min-h-[calc(100vh-9rem)] p-6">
        <h1 class="large-title mb-4">Help</h1>
        <p class="text-lg">Hello! hopefully this page will guide you through Astrum.</p>
        <ul class="m-6 space-y-12">
            <li>
                <h2 id="post-watermark-options" class="text-2xl mb-3 bg-neutral-200 dark:bg-neutral-700 p-2">Post Watermark Options</h2>

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
            </li>

            <li>
                <h2 id="post-frame-upload-guidelines" class="text-2xl mb-3 bg-neutral-200 dark:bg-neutral-700 p-2">Post Frame Upload Guidelines</h2>
                <p>Let's assume you want to upload this post frame:</p>
                <img src="{{ asset('images/pf_guide_1.png') }}" alt="" class="w-28 h-28 m-8">
                <h3 class="text-lg my-2 font-bold">Percentage</h3>
                <p>We count the parts of the frame according to this picture:</p>
                <img id="img" src="{{ asset('images/pf_guide_2.png') }}" alt="" class="w-72 my-3">
                <p>And so the 'Percentage' will be 12.5. The width is how large it will be:</p>
                <h3 class="text-lg my-2 font-bold">Width</h3>
                <p class="mb-3">First is Width: 10, the second 20.</p>
                <div class="grid grid-cols-2 gap-10">
                    <article style="border-image: url('{{ asset('images/pf_guide_1.png') }}') 12.5% round;
                    border-style: solid; border-width: 10px !important;"
                        class="m-5 first-letter:flex flex-col justify-between border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid">
                        <div class="">
                            <a class="">
                                <div class="flex justify-between items-center">
                                    <h1 class="medium-title mr-2">
                                        Title
                                    </h1>
                                    <span>2 hours ago</span>
                                </div>
                            </a>
                            <h2 class="mb-2">
                                <a>
                                    johndoe
                                </a>
                            </h2>
                            <img class="w-full" src="" alt="">

                            <div>
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum deserunt iste quos consequatur
                                doloremque? Obcaecati excepturi earum voluptatum? Quia mollitia odit saepe, et eveniet blanditiis
                                eum laboriosam debitis provident numquam?
                            </div>
                        </div>
                        <div class="">
                            <footer class="mt-4 flex justify-between">
                                <a class="space-x-1"><span>5</span><i class="fa-regular fa-comment"></i></a>

                                <x-secondary-button type="button" class="space-x-0.5 pointer-events-none">
                                    <span>206</span>
                                    <i class="fa-regular fa-heart"></i>
                                </x-secondary-button>
                            </footer>
                        </div>
                    </article>
                    <article style="border-image: url('{{ asset('images/pf_guide_1.png') }}') 12.5% round;
                border-style: solid; border-width: 20px !important;"
                        class="m-5 first-letter:flex flex-col justify-between border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid">
                        <div class="">
                            <a class="">
                                <div class="flex justify-between items-center">
                                    <h1 class="medium-title mr-2">
                                        Title
                                    </h1>
                                    <span>2 hours ago</span>
                                </div>
                            </a>
                            <h2 class="mb-2">
                                <a>
                                    johndoe
                                </a>
                            </h2>
                            <img class="w-full" src="" alt="">

                            <div>
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum deserunt iste quos consequatur
                                doloremque? Obcaecati excepturi earum voluptatum? Quia mollitia odit saepe, et eveniet blanditiis
                                eum laboriosam debitis provident numquam?
                            </div>
                        </div>
                        <div class="">
                            <footer class="mt-4 flex justify-between">
                                <a class="space-x-1"><span>5</span><i class="fa-regular fa-comment"></i></a>

                                <x-secondary-button type="button" class="space-x-0.5 pointer-events-none">
                                    <span>206</span>
                                    <i class="fa-regular fa-heart"></i>
                                </x-secondary-button>
                            </footer>
                        </div>
                    </article>
                </div>
            </li>
        </ul>
    </div>
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
