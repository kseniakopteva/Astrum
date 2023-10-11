<?php

namespace App\Http\Controllers;

use App\Models\PostFrame;
use App\Models\ProfilePictureFrame;
use App\Models\Wallpaper;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class StarshopController extends Controller
{
    public function index()
    {
        return $this->wallpapers_index();
    }

    /* -------------------------------------------------------------------------- */
    /*                                 Wallpapers                                 */
    /* -------------------------------------------------------------------------- */

    public function wallpapers_index()
    {
        return view('starshop.wallpapers.index', [
            'wallpapers' => Wallpaper::all()
        ]);
    }
    public function wallpapers_create()
    {
        return view('starshop.wallpapers.create');
    }
    public function wallpapers_store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:4000',
            'price' => 'max:10000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\wallpapers');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->resize(2000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '\\' . $imageName);

        Wallpaper::create($attributes);

        return back()
            ->with('success', 'You have successfully created a wallpaper!');
    }

    /* -------------------------------------------------------------------------- */
    /*                           Profile Picture Frames                           */
    /* -------------------------------------------------------------------------- */

    public function profile_picture_frames_index()
    {
        return view('starshop.profile-picture-frames.index', [
            'profile_picture_frames' => ProfilePictureFrame::all(),
        ]);
    }
    public function profile_picture_frames_create()
    {
        return view('starshop.profile-picture-frames.create');
    }
    public function profile_picture_frames_store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:4000',
            'price' => 'max:10000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\profile-picture-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '\\' . $imageName);

        ProfilePictureFrame::create($attributes);

        return back()
            ->with('success', 'You have successfully created a profile picture frame!');
    }

    /* -------------------------------------------------------------------------- */
    /*                                 Post Frames                                */
    /* -------------------------------------------------------------------------- */

    public function post_frames_index()
    {
        return view('starshop.post-frames.index', [
            'post_frames' => PostFrame::all()
        ]);
    }
    public function post_frames_create()
    {
        return view('starshop.post-frames.create');
    }
    public function post_frames_store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:4000',
            'price' => 'max:10000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\post-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->resize(2000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '\\' . $imageName);

        PostFrame::create($attributes);

        return back()
            ->with('success', 'You have successfully created a post frame!');
    }

    public function redirect()
    {
        return back()->with('success', 'You must be logged in to access this!');
    }
}
