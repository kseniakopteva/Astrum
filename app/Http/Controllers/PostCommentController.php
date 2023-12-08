<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostCommentLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function store(Post $post)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t write comments because you are banned.');

        request()->validate([
            'body' => 'required|max:4000'
        ]);

        // price to post a comment is 1 star
        $u = auth()->user();
        $price = 1;
        if ($u->stars >= $price) {
            $post->comments()->create([
                'user_id' => request()->user()->id,
                'body' => request()->input('body')
            ]);
            $u->stars -= $price;
            $u->save();

            // give author 3 stars as a reward for a new comment
            // (if it is not the same user)
            if ($post->author->id !== $u->id) {
                $post->author->stars += 3;
                $post->author->save();
            }

            return back()
                ->with('success', 'You have successfully created a comment!');
        }
        return back()
            ->with('error', 'You don\'t have enough money!');

        return back();
    }

    public function toggleLike(PostComment $postcomment)
    {
        // if user has never liked the comment, create new db row
        if (!PostCommentLike::where('user_id', auth()->id())->where('post_comment_id', $postcomment->id)->exists()) {
            PostCommentLike::create([
                'user_id' => auth()->user()->id,
                'post_comment_id' => $postcomment->id,
                'liked' => 1
            ]);
            // if it is not this user's comment, give money (1 star) to the user
            if ($postcomment->author->id !== auth()->user()->id) {
                $postcomment->author->stars++;
                $postcomment->author->save();
            }
        } // if user has liked this comment before (record exists)
        else {
            $postCommentLike = PostCommentLike::where('user_id', auth()->id())->where('post_comment_id', $postcomment->id);
            // check if the existing record says the comment is liked or not, toggle it
            $isLiked = $postcomment->isLiked($postcomment);
            if (!$isLiked) {
                $postCommentLike->update(['liked' => 1]);
            } else {
                $postCommentLike->update(['liked' => 0]);
            }
        }
        return back();
    }

    public function destroy(Request $request)
    {
        $pc = PostComment::find($request->id);
        if (Auth::user()->id === $pc->author->id) {
            $pc->delete();
            return back();
        } else {
            return redirect()->route('explore');
        }
    }
}
