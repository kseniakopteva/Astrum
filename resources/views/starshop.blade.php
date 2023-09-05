<x-layout>
    <div class="shop-header">
        <h1 class="large-title">Star Shop</h1>
        <span>Your stars: 100<i class="fa-solid fa-star"></i></span>
    </div>
    <div class="shop">
        <input class="radio hidden" id="one" name="group" type="radio" checked>
        <input class="radio hidden" id="two" name="group" type="radio">

        <div class="tabs">
            <label class="tab" id="one-tab" for="one">Wallpapers</label>
            <label class="tab" id="two-tab" for="two">Frames</label>
        </div>

        <div class="panels">
            <div class="panel" id="one-panel">
                <div class="medium-title">Profile Wallpapers</div>
                <p>Stylish wallpapers for your profile that will make your posts shine!</p>
                <div class="wallpapers">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="wallpaper">
                            <img src="https://placehold.co/600x400" alt="">
                            <h3 class="small-title"><a href="">Wallpaper {{ $i }}</a></h3>
                            <p class="wallpaper-description">Description goes here Lorem ipsum dolor sit amet
                                consectetur adipisicing elit. Fuga aliquid nostrum rem ipsam voluptatibus asperiores
                                debitis </p>
                            <div class="wallpaper-footer">
                                <span>Author: <a href="">John Doe</a></span>
                                <span>1000<i class="fa-solid fa-star"></i></span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="panel" id="two-panel">
                <div class="medium-title">Profile Picture Frames</div>
                <p>Frames for your profile picture that will bring out the best out of your picture!</p>
                <div class="frames">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="frame">
                            <img src="https://placehold.co/200x200" alt="">
                            <h3 class="small-title"><a href="">Frame {{ $i }}</a></h3>
                            <p class="frame-description">Description goes here Lorem ipsum dolor sit amet
                                consectetur adipisicing elit. Fuga aliquid nostrum rem ipsam voluptatibus asperiores
                                debitis </p>
                            <div class="frame-footer">
                                <span>Author: <a href="">John Doe</a></span>
                                <span>1000<i class="fa-solid fa-star"></i></span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

</x-layout>
