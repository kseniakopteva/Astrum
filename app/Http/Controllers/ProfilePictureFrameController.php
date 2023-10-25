<?php

namespace App\Http\Controllers;

use App\Models\ProfilePictureFrame;
use App\Models\ProfilePictureFrameLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProfilePictureFrameController extends Controller
{

    public function index()
    {
        return view('starshop.profile-picture-frames.index', [
            'profile_picture_frames' => ProfilePictureFrame::all(),
        ]);
    }

    public function show(ProfilePictureFrame $profile_picture_frame)
    {
        return view('starshop.profile-picture-frames.show', [
            'profile_picture_frame' => $profile_picture_frame
        ]);
    }

    public function create()
    {
        return view('starshop.profile-picture-frames.create');
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

        $path = storage_path('app\public\images\\profile-picture-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->fit(2000)->save($path . '\\' . $imageName);

        $profile_picture_frame = ProfilePictureFrame::create($attributes);

        return redirect()->route('starshop.profile-picture-frames.show', ['profile_picture_frame' => $profile_picture_frame->id])
            ->with('success', 'You have successfully created a profile picture frame!');
    }

    public function buy(Request $request)
    {
        if (!auth()->check())
            return back();

        $ppf = ProfilePictureFrame::find($request->id);
        if (auth()->user()->stars >= $ppf->price) {
            auth()->user()->ownedProfilePictureFrames()->attach([
                'profile_picture_frame_id' => $ppf->id,
                'user_id' => auth()->user()->id
            ]);
            auth()->user()->stars -= $ppf->price;
            auth()->user()->save();
            return back()
                ->with('success', 'You have successfully purchased a profile picture frame!');
        }
        return back()
            ->with('success', 'You don\'t have enough money!');
    }


    public function destroy(Request $request)
    {
        $ppf = ProfilePictureFrame::find($request->id);
        if (Auth::user()->id === $ppf->author->id) {
            $ppf->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }


    public function toggleLike(ProfilePictureFrame $profile_picture_frame)
    {
        // never liked
        if (!ProfilePictureFrameLike::where('user_id', auth()->id())->where('profile_picture_frame_id', $profile_picture_frame->id)->exists()) {
            ProfilePictureFrameLike::create([
                'user_id' => auth()->id(),
                'profile_picture_frame_id' => $profile_picture_frame->id,
                'liked' => 1
            ]);
            // if it is not your own, give money to user
            if ($profile_picture_frame->author->id !== auth()->user()->id) {
                $profile_picture_frame->author->stars += 2;
                $profile_picture_frame->author->save();
            }
        } // record exists
        else {
            $profilePictureFrameLike = ProfilePictureFrameLike::where('user_id', auth()->id())->where('profile_picture_frame_id', $profile_picture_frame->id);
            $isLiked = $profile_picture_frame->isLiked($profile_picture_frame);
            if (!$isLiked) {
                $profilePictureFrameLike->update(['liked' => 1]);
            } else {
                $profilePictureFrameLike->update(['liked' => 0]);
            }
        }
        return back();
    }
}
