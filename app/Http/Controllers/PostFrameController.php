<?php

namespace App\Http\Controllers;

use App\Models\PostFrame;
use App\Models\PostFrameLike;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostFrameController extends Controller
{
    public function index()
    {
        return view('starshop.post-frames.index', [
            'post_frames' => PostFrame::all()
        ]);
    }

    public function show(PostFrame $post_frame)
    {
        return view('starshop.profile-picture-frames.show', [
            'post-frame' => $post_frame
        ]);
    }

    public function create()
    {
        return view('starshop.post-frames.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:700',
            'width' => 'required',
            'price' => 'required|max:10000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = PostController::make_slug($attributes['name']);

        $path = storage_path('app\public\images\\post-frames');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->fit(2000)->save($path . '\\' . $imageName);

        $post_frame = PostFrame::create($attributes);

        return redirect()->route('starshop.post-frames.show', ['post_frame' => $post_frame->id])
            ->with('success', 'You have successfully created a post frame!');
    }

    public function toggleLike(PostFrame $post_frame)
    {
        // never liked
        if (!PostFrameLike::where('user_id', auth()->id())->where('post_frame_id', $post_frame->id)->exists()) {
            PostFrameLike::create([
                'user_id' => auth()->id(),
                'post_frame_id' => $post_frame->id,
                'liked' => 1
            ]);
            // if it is not your own, give money to user
            if ($post_frame->author->id !== auth()->user()->id) {
                $post_frame->author->stars += 1;
                $post_frame->author->save();
            }
        } // record exists
        else {
            $postFrameLike = PostFrameLike::where('user_id', auth()->id())->where('post_frame_id', $post_frame->id);
            $isLiked = $post_frame->isLiked($post_frame);
            if (!$isLiked) {
                $postFrameLike->update(['liked' => 1]);
            } else {
                $postFrameLike->update(['liked' => 0]);
            }
        }
        return back();
    }
}
