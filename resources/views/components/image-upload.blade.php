<div {{ $attributes->merge(['class' => '']) }}>
    <div class="" x-data="previewImage()">

        <label for="image" class="relative cursor-pointer">
            <div
                class="w-full mt-1 h-80 flex flex-col items-center justify-center overflow-hidden bg-neutral-300 border border-neutral-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 rounded-md">

                <img id="imagePreview" x-show="imageUrl" :src="imageUrl" class="w-full object-cover">
                {{-- <div id="imagePlaceholder" x-show="!imageUrl" class="text-neutral-300 flex flex-col items-center"
                    {{-- style="background-image: url('/images/placeholder.png')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 " fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <div>Image Preview</div>
            </div> --}}

            </div>
            <div class="w-full h-full absolute bottom-0 flex items-end pb-4 justify-center">
                <input style="display: none" class="" type="file" name="image" id="image"
                    @change="fileChosen">

                <input
                    class="inline-flex items-center px-4 py-2 cursor-pointer
                    bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150"
                    type="button" value="Browse..." onclick="document.getElementById('image').click();" />
            </div>
        </label>



    </div>
</div>
<script>
    function previewImage() {
        return {
            imageUrl: "",

            fileChosen(event) {
                this.fileToDataUrl(event, (src) => (this.imageUrl = src));
            },

            fileToDataUrl(event, callback) {
                if (!event.target.files.length) return;

                let file = event.target.files[0],
                    reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = (e) => callback(e.target.result);


            },
        };
    }
</script>
