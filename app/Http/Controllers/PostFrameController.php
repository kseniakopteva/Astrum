<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\PostFrame;
use App\Models\PostFrameLike;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostFrameController extends Controller
{
    public function index()
    {
        $banned_users = Ban::where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())->pluck('user_id');

        $pf = PostFrame::whereNotIn('user_id', $banned_users)->where('removed', false)->get();

        return view('starshop.post-frames.index', [
            'post_frames' => $pf
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
        if (auth()->user()->isBanned(auth()->user()))
            return back()->with('success', 'You can\'t create because you are banned.');

        $attributes = $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'max:700',
            'width' => 'required',
            'percentage' => 'required',
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

        $tags = array_filter(array_map('trim', explode(',', $request['tags'])));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag, 'slug' => str_replace(' ', '_', $tag)])->save();
        }
        $tags = Tag::whereIn('name', $tags)->get()->pluck('id');
        $post_frame->tags()->sync($tags);

        return redirect()->route('starshop.post-frames.show', ['post_frame' => $post_frame->slug])
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

    public function buy(Request $request)
    {
        if (!auth()->check())
            return back();

        $user = auth()->user();

        $pf = PostFrame::find($request->id);

        if ($user->stars < $pf->price) {
            return back()
                ->with('success', 'You don\'t have enough money!');
        }

        if (!$user->ownedPostFrames()
            ->where('post_frame_id', $pf->id)
            ->where('post_frame_user.user_id', $user->id)
            ->exists()) {

            $user->ownedPostFrames()->attach([
                'post_frame_id' => $pf->id
            ]);
        } else {
            $existing = $user->ownedPostFrames()
                ->where('post_frame_id', $pf->id)
                ->where('post_frame_user.user_id', $user->id)->first();
            $new_amount = $existing->pivot->amount + 1;

            $user->ownedPostFrames()->updateExistingPivot($pf->id, ['amount' => $new_amount]);
        }

        $user->stars -= $pf->price;
        $user->save();

        return back()
            ->with('success', 'You have successfully purchased a post frame!');
    }

    public function destroy(Request $request)
    {
        $pf = PostFrame::find($request->id);
        if (auth()->user()->id === $pf->author->id) {
            $pf->delete();
            return redirect('/starshop');
        } else {
            return redirect()->route('explore');
        }
    }
}
