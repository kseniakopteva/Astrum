<?php

namespace App\Http\Controllers;

use App\Models\PostFrame;
use App\Models\PostFrameLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostFrameController extends Controller
{
    public function index()
    {
        return view('starshop.post-frames.index', [
            'post_frames' => PostFrame::whereNotIn('user_id', User::getBannedUserIds())->where('removed', false)->get()
        ]);
    }

    public function show(PostFrame $post_frame)
    {
        return view('starshop.post-frames.show', [
            'post_frame' => $post_frame
        ]);
    }

    public function create()
    {
        return view('starshop.post-frames.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t create because you are banned.');

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'max:700',
            'width' => 'required|max:60',
            'percentage' => 'required',
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

        // saving image, cropping it into a square and resizing to 500px
        $path = public_path('images\\post-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);
        Image::make($path . '/' . $imageName)->fit(500)->save($path . '/' . $imageName);

        $post_frame = PostFrame::create($attributes);
        $u->stars -= $price;
        $u->save();

        return redirect()->route('starshop.post-frames.show', ['post_frame' => $post_frame->slug])
            ->with('success', 'You have successfully created a post frame!');
    }

    public function toggleLike(PostFrame $post_frame)
    {
        // if user has never liked the post frame, create new db row
        if (!PostFrameLike::where('user_id', auth()->id())->where('post_frame_id', $post_frame->id)->exists()) {
            PostFrameLike::create([
                'user_id' => auth()->id(),
                'post_frame_id' => $post_frame->id,
                'liked' => 1
            ]);
            // if it is not this user's post, give money (2 stars) to the user
            if ($post_frame->author->id !== auth()->user()->id) {
                $post_frame->author->stars += 2;
                $post_frame->author->save();
            }
        } // if user has liked this post frame before (record exists)
        else {
            $postFrameLike = PostFrameLike::where('user_id', auth()->id())->where('post_frame_id', $post_frame->id);
            // check if the existing record says the post frame is liked or not, toggle it
            $isLiked = $post_frame->isLiked($post_frame);
            if (!$isLiked) {
                $postFrameLike->update(['liked' => 1]);
            } else {
                $postFrameLike->update(['liked' => 0]);
            }
        }
        return back();
    }

    public function buy(Request $request)
    {
        if (!auth()->check())
            return back();

        $user = auth()->user();

        // users can't buy, only creators and more
        if ($user->role == 'user') {
            return back();
        }

        $pf = PostFrame::find($request->id);

        if ($user->stars < $pf->price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        // if user doesn't have this post frame, make a new db record
        if (!$user->ownedPostFrames()
            ->where('post_frame_id', $pf->id)
            ->where('post_frame_user.user_id', $user->id)
            ->exists()) {

            $user->ownedPostFrames()->attach([
                'post_frame_id' => $pf->id
            ]);
        } // else increase the amount of it
        else {
            $existing = $user->ownedPostFrames()
                ->where('post_frame_id', $pf->id)
                ->where('post_frame_user.user_id', $user->id)->first();
            $new_amount = $existing->pivot->amount + 1;

            $user->ownedPostFrames()->updateExistingPivot($pf->id, ['amount' => $new_amount]);
        }

        // remove the buyer's stars
        $user->stars -= $pf->price;
        $user->save();

        // add to seller's stars
        $pf->author->stars += $pf->price;
        $pf->author->save();

        return back()
            ->with('success', 'You have successfully purchased a post frame!');
    }

    public function destroy(Request $request)
    {
        $pf = PostFrame::find($request->id);
        if (auth()->user()->id === $pf->author->id) {
            // before deleting the post frame, remove the image from the storage
            unlink(public_path('images/post-frames/' . $pf->image));
            $pf->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }
}
