<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Wallpaper;
use App\Models\WallpaperLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class WallpaperController extends Controller
{
    public function index()
    {
        return view('starshop.wallpapers.index', [
            'wallpapers' => Wallpaper::whereNotIn('user_id', User::getBannedUserIds())->where('removed', false)->latest()->get()
        ]);
    }

    public function show(Wallpaper $wallpaper)
    {
        if (!$wallpaper->removed)
            return view('starshop.wallpapers.show', [
                'wallpaper' => $wallpaper
            ]);
        else return redirect()->route('starshop');
    }

    public function create()
    {
        return view('starshop.wallpapers.create');
    }
    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t create because you are banned.');

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'max:700',
            'price' => 'required|numeric|min:800|max:10000'
        ]);

        // price to make a wallpaper is the user's set price for it
        $price = $attributes['price'];
        $u = auth()->user();
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        // saving image, making it 1920x1080
        $path = public_path('images\\wallpapers');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);
        Image::make($path . '/' . $imageName)->fit(1920, 1080)->save($path . '/' . $imageName);

        $wallpaper = Wallpaper::create($attributes);
        $u->stars -= $price;
        $u->save();

        return redirect()->route('starshop.wallpapers.show', ['wallpaper' => $wallpaper->slug])
            ->with('success', 'You have successfully created a wallpaper!');
    }

    public function buy(Request $request)
    {
        if (!auth()->check())
            return back();

        $wallpaper = Wallpaper::find($request->id);
        if (auth()->user()->stars >= $wallpaper->price) {
            auth()->user()->ownedWallpapers()->attach([
                'wallpaper_id' => $wallpaper->id,
                'user_id' => auth()->user()->id
            ]);

            // remove the buyer's stars
            auth()->user()->stars -= $wallpaper->price;
            auth()->user()->save();

            // add to seller's stars
            $wallpaper->author->stars += $wallpaper->price;
            $wallpaper->author->save();

            return back()
                ->with('success', 'You have successfully purchased a wallpaper!');
        }
        return back()
            ->with('error', 'You don\'t have enough money!');
    }

    public function destroy(Request $request)
    {
        $wallpaper = Wallpaper::find($request->id);
        if (Auth::user()->id === $wallpaper->author->id) {
            // before deleting the wallpaper, remove the image from the storage
            unlink(public_path('images/wallpapers/' . $wallpaper->image));
            $wallpaper->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }

    public function toggleLike(Wallpaper $wallpaper)
    {
        // if user has never liked the wallpaper, create new db row
        if (!WallpaperLike::where('user_id', auth()->id())->where('wallpaper_id', $wallpaper->id)->exists()) {
            WallpaperLike::create([
                'user_id' => auth()->id(),
                'wallpaper_id' => $wallpaper->id,
                'liked' => 1
            ]);
            // if it is not this user's wallpaper, give money (2 stars) to the user
            if ($wallpaper->author->id !== auth()->user()->id) {
                $wallpaper->author->stars += 2;
                $wallpaper->author->save();
            }
        } // if user has liked this wallpaper before (record exists)
        else {
            $wallpaperLike = WallpaperLike::where('user_id', auth()->id())->where('wallpaper_id', $wallpaper->id);
            // check if the existing record says the wallpaper is liked or not, toggle it
            $isLiked = $wallpaper->isLiked($wallpaper);
            if (!$isLiked) {
                $wallpaperLike->update(['liked' => 1]);
            } else {
                $wallpaperLike->update(['liked' => 0]);
            }
        }
        return back();
    }
}
