<?php

namespace App\Http\Controllers;

use App\Models\ProfilePictureFrame;
use App\Models\ProfilePictureFrameLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProfilePictureFrameController extends Controller
{
    public function index()
    {
        return view('starshop.profile-picture-frames.index', [
            'profile_picture_frames' => ProfilePictureFrame::whereNotIn('user_id', User::getBannedUserIds())->where('removed', false)->get()
        ]);
    }

    public function show(ProfilePictureFrame $profile_picture_frame)
    {
        if (!$profile_picture_frame->removed)
            return view('starshop.profile-picture-frames.show', [
                'profile_picture_frame' => $profile_picture_frame
            ]);
        else return redirect()->route('starshop');
    }

    public function create()
    {
        return view('starshop.profile-picture-frames.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t create because you are banned.');

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'max:700',
            'price' => 'required|numeric|min:500|max:10000'
        ]);

        // price to make a post frame is the user's set price for it
        $price = $attributes['price'];
        $u = auth()->user();
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        // saving image, cropping it into a square and resizing to 700px
        $path = public_path('images\\profile-picture-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);
        Image::make($path . '/' . $imageName)->fit(700)->save($path . '/' . $imageName);

        $profile_picture_frame = ProfilePictureFrame::create($attributes);
        $u->stars -= $price;
        $u->save();

        return redirect()->route('starshop.profile-picture-frames.show', ['profile_picture_frame' => $profile_picture_frame->slug])
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

            // remove the buyer's stars
            auth()->user()->stars -= $ppf->price;
            auth()->user()->save();

            // add to seller's stars
            $ppf->author->stars += $ppf->price;
            $ppf->author->save();

            return back()
                ->with('success', 'You have successfully purchased a profile picture frame!');
        }
        return back()
            ->with('error', 'You don\'t have enough money!');
    }

    public function destroy(Request $request)
    {
        $ppf = ProfilePictureFrame::find($request->id);
        if (Auth::user()->id === $ppf->author->id) {
            // before deleting the profile picture frame, remove the image from the storage
            unlink(public_path('images/profile-picture-frames/' . $ppf->image));
            $ppf->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }

    public function toggleLike(ProfilePictureFrame $profile_picture_frame)
    {
        // if user has never liked the profile picture frame, create new db row
        if (!ProfilePictureFrameLike::where('user_id', auth()->id())->where('profile_picture_frame_id', $profile_picture_frame->id)->exists()) {
            ProfilePictureFrameLike::create([
                'user_id' => auth()->id(),
                'profile_picture_frame_id' => $profile_picture_frame->id,
                'liked' => 1
            ]);
            // if it is not this user's profile picture frame, give money (2 stars) to the user
            if ($profile_picture_frame->author->id !== auth()->user()->id) {
                $profile_picture_frame->author->stars += 2;
                $profile_picture_frame->author->save();
            }
        } // if user has liked this profile picture frame before (record exists)
        else {
            $profilePictureFrameLike = ProfilePictureFrameLike::where('user_id', auth()->id())->where('profile_picture_frame_id', $profile_picture_frame->id);
            // check if the existing record says the profile picture frame is liked or not, toggle it
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
