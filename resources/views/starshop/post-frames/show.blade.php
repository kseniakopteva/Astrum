<x-starshop-product-layout :item="$post_frame" type="post-frame">
    <div class="border-b border-neutral-200 dark:border-neutral-700 pb-16 grid grid-cols-5">
        <article
            style="border-image: url('{{ asset('images/post-frames/' . $post_frame->image) }}') {{ $post_frame->percentage }}% round;
                    border-style: solid; border-width: {{ $post_frame->width }}px !important;"
            class="col-start-2 col-span-3 first-letter:flex flex-col justify-between border bg-white border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 rounded-lg p-4 pt-4 mb-4 break-inside-avoid">
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
    </div>
</x-starshop-product-layout>
