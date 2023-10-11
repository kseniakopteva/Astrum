<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        <div class="md:grid md:grid-cols-4 md:gap-4">
            <div class="space-y-6 col-span-2">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                        :value="old('username', $user->username)" required autofocus autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                </div>

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $user->name)" autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-neutral-800 dark:text-neutral-200">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:focus:ring-offset-neutral-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="bio" :value="__('Bio')" />
                    <x-textarea id="bio" name="bio" type="text" class="mt-1 block w-full" rows="7"
                        autofocus>{{ old('bio', $user->bio) }}</x-textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>

            </div>

            <div class="col-span-2 grid grid-cols-2 gap-6 w-full">
                @if ($user->isCreatorOrMore($user))
                    <div class="space-y-4 mt-6 md:ml-10">
                        <span class="block font-medium text-sm text-neutral-700 dark:text-neutral-300">Badge</span>
                        @foreach ($badges as $badge)
                            <div class="flex items-center">
                                <input
                                    class="
                         text-lime-500 dark:text-lime-600 bg-neutral-100 border-neutral-300
                          focus:ring-lime-500 dark:focus:ring-lime-600 dark:ring-offset-neutral-800
                           focus:ring-2 dark:bg-neutral-700 dark:border-neutral-600"
                                    <?php if ($badge->id === $user->badge->id) {
                                        echo 'checked';
                                    } ?> type="radio" name="badge_id" id="{{ $badge->id }}"
                                    value="{{ $badge->id }}">
                                <label for="{{ $badge->id }}"
                                    class="rounded-md py-1 px-2 bg-{{ $badge->lightcolor }} dark:bg-{{ $badge->darkcolor }} dark:text-neutral-300 ml-2 font-medium text-sm text-neutral-700">{{ ucfirst($badge->name) }}</label>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div
                    class="space-y-4 mt-6 m-auto
                @if (!$user->isCreatorOrMore($user)) col-span-2 @endif
                ">
                    <div>
                        <span class="block font-medium text-sm text-neutral-700 dark:text-neutral-300">Profile
                            Image</span>

                        <div class="h-40 w-40 md:h-40 md:w-40 lg:h-52 lg:w-52 xl:h-60 xl:w-60" x-data="previewImage()">
                            <label for="image" class="relative cursor-pointer">
                                <div
                                    class=" flex justify-center items-center overflow-hidden h-40 w-40 md:h-40 md:w-40 lg:h-52 lg:w-52 xl:h-60 xl:w-60 mt-1 flex-col bg-neutral-300 border border-neutral-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 rounded-md">

                                    <img id="imagePreview" x-show="imageUrl" :src="imageUrl"
                                        class="object-cover w-full h-full">
                                    <div id="imagePlaceholder" x-show="!imageUrl"
                                        class="text-neutral-300 flex flex-col items-center w-full h-full"
                                        style="background-image: url('{{ asset('storage/images/profile-pictures/' . auth()->user()->image) }}'); background-size: cover; background-position: center center;">
                                    </div>

                                </div>
                                <div class="w-full h-full absolute bottom-0 flex items-end pb-4 justify-center">
                                    <input style="display: none" class="" type="file" name="image"
                                        id="image" @change="fileChosen">

                                    <input
                                        class="inline-flex items-center px-4 py-2 cursor-pointer
                                        bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150"
                                        type="button" value="Change..."
                                        onclick="document.getElementById('image').click();" />
                                </div>
                            </label>
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


                        <x-input-error :messages="$errors->get('image')" class="mt-2" />

                        <x-danger-link href="/settings/remove" class="mt-6">Remove Image</x-danger-link>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 justify-end">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Saved.') }}</p>
            @endif
            <x-primary-button class="w-60 justify-center">{{ __('Save') }}</x-primary-button>

        </div>
    </form>
</section>
