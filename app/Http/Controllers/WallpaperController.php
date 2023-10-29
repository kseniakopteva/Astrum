<?php

namespace App\Http\Controllers;

use App\Models\Tag;
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
            'wallpapers' => Wallpaper::all()
        ]);
    }

    public function show(Wallpaper $wallpaper)
    {
        return view('starshop.wallpapers.show', [
            'wallpaper' => $wallpaper
        ]);
    }

    public function create()
    {
        return view('starshop.wallpapers.create');
    }
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:700',
            'price' => 'required|max:10000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\wallpapers');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->fit(1920, 1080)->save($path . '\\' . $imageName);

        $wallpaper = Wallpaper::create($attributes);

        $tags = array_map('trim', explode(',', $request['tags']));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag, 'slug' => str_replace(' ', '_', $tag)])->save();
        }
        $tags = Tag::whereIn('name', $tags)->get()->pluck('id');
        $wallpaper->tags()->sync($tags);

        return redirect()->route('starshop.wallpapers.show', ['wallpaper' => $wallpaper->id])
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
            auth()->user()->stars -= $wallpaper->price;
            auth()->user()->save();
            return back()
                ->with('success', 'You have successfully purchased a wallpaper!');
        }
        return back()
            ->with('success', 'You don\'t have enough money!');
    }

    public function destroy(Request $request)
    {
        $wallpaper = Wallpaper::find($request->id);
        if (Auth::user()->id === $wallpaper->author->id) {
            $wallpaper->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }

    public function toggleLike(Wallpaper $wallpaper)
    {
        // never liked
        if (!WallpaperLike::where('user_id', auth()->id())->where('wallpaper_id', $wallpaper->id)->exists()) {
            WallpaperLike::create([
                'user_id' => auth()->id(),
                'wallpaper_id' => $wallpaper->id,
                'liked' => 1
            ]);
            // if it is not your own, give money to user
            if ($wallpaper->author->id !== auth()->user()->id) {
                $wallpaper->author->stars += 2;
                $wallpaper->author->save();
            }
        } // record exists
        else {
            $wallpaperLike = WallpaperLike::where('user_id', auth()->id())->where('wallpaper_id', $wallpaper->id);
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
