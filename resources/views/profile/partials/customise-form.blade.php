<section>
    <header>
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
            {{ __('Customise Your Profile') }}
        </h2>

        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            {{ __('Make it stand out!') }}
        </p>
    </header>

    <section class="mt-4">
        <h3 class="mb-1">Your Wallpapers</h3>
        <div class="grid grid-cols-6 gap-2 w-full h-full">
            <form action="{{ route('set_current_wallpaper') }}" method="POST" class="h-[calc(6.67rem)]">
                @csrf
                <input type="hidden" value="" id="id" name="id">
                <button type="submit" class="w-full h-full">
                    <div
                        class="bg-black bg-opacity-30 grid place-content-center w-full h-full
                    <?php
                    if (is_null(auth()->user()->currentWallpaper)) {
                        echo 'border-2 border-lime-400 border-dashed';
                    } else {
                        echo 'border border-neutral-900';
                    }
                    ?>
                     rounded-md">
                        (None)
                    </div>
                </button>
            </form>
            @foreach (auth()->user()->ownedWallpapers as $wallpaper)
                <form action="{{ route('set_current_wallpaper') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $wallpaper->id }}" name="id">
                    <button type="submit">
                        <img src="{{ asset('storage/images/wallpapers/' . $wallpaper->image) }}" alt=""
                            class="rounded-md
                        <?php
                        if (!is_null(auth()->user()->currentWallpaper)) {
                            if ($wallpaper->id === auth()->user()->currentWallpaper->id) {
                                echo 'border-2 border-lime-400 border-dashed';
                            } else {
                                echo 'border border-neutral-900';
                            }
                        }
                        ?>">
                    </button>
                </form>
            @endforeach
        </div>
    </section>

    <section class="mt-4">
        <h3 class="mb-1">Your Profile Picture Frames</h3>
        <div class="grid grid-cols-8 gap-2 w-full">
            <form action="{{ route('set_current_profile_picture_frame') }}" method="POST" class="h-[calc(8.7rem)]">
                @csrf
                <input type="hidden" value="" id="id" name="id">
                <button type="submit" class="w-full h-full">
                    <div
                        class="bg-black bg-opacity-30 grid place-content-center w-full h-full
                    <?php
                    if (is_null(auth()->user()->currentProfilePictureFrame)) {
                        echo 'border-2 border-lime-400 border-dashed';
                    } else {
                        echo 'border border-neutral-900';
                    }
                    ?>
                     rounded-md">
                        (None)
                    </div>
                </button>
            </form>
            @foreach (auth()->user()->ownedProfilePictureFrames as $ppf)
                <form action="{{ route('set_current_profile_picture_frame') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $ppf->id }}" name="id">
                    <button type="submit">
                        <img src="{{ asset('storage/images/profile-picture-frames/' . $ppf->image) }}" alt=""
                            class="rounded-md
                        <?php
                        if (!is_null(auth()->user()->currentProfilePictureFrame)) {
                            if ($ppf->id === auth()->user()->currentProfilePictureFrame->id) {
                                echo 'border-2 border-lime-400 border-dashed';
                            } else {
                                echo 'border border-neutral-900';
                            }
                        }
                        ?>">
                    </button>
                </form>
            @endforeach
        </div>
    </section>

    <section class="mt-4">
        <h3 class="mb-1">Your Colours</h3>
        <div class="grid grid-cols-12 gap-2 w-full">
            <form action="{{ route('set_current_colour') }}" method="POST" class="h-20 w-20">
                @csrf
                <input type="hidden" value="" id="id" name="id">
                <button type="submit" class="w-full h-full">
                    <div
                        class="bg-black bg-opacity-30 grid place-content-center w-full h-full
                    <?php
                    if (is_null(auth()->user()->colour)) {
                        echo 'border-2 border-lime-400 border-dashed';
                    } else {
                        echo 'border border-neutral-900';
                    }
                    ?>
                     rounded-md">
                        (Default)
                    </div>
                </button>
            </form>
            @foreach (auth()->user()->ownedColours as $colour)
                <form action="{{ route('set_current_colour') }}" method="POST" class="h-20 w-20">
                    @csrf
                    <input type="hidden" value="{{ $colour->id }}" name="id">
                    <button type="submit">
                        <div
                            class="rounded-md h-20 w-20 bg-{{ $colour->lightcolor }} dark:bg-{{ $colour->darkcolor }}
                        <?php
                        if (!is_null(auth()->user()->colour)) {
                            if ($colour->id === auth()->user()->colour->id) {
                                echo 'border-2 border-lime-400 border-dashed';
                            } else {
                                echo 'border border-neutral-900';
                            }
                        }
                        ?>">
                        </div>
                    </button>
                </form>
            @endforeach
        </div>
    </section>
</section>
