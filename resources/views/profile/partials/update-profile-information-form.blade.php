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

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        <div class="md:grid md:grid-cols-2 md:gap-4">
            <div class="space-y-6">
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
            <div class="space-y-4 mt-6 ml-10">
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
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
