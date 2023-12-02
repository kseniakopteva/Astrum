<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostCommentLike;
use App\Models\User;

class PostCommentController extends Controller
{
    public function store(Post $post)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t write comments because you are banned.');

        request()->validate([
            'body' => 'required|max:4000'
        ]);

        $u = auth()->user();
        $price = 1;
        if ($u->stars >= $price) {
            $post->comments()->create([
                'user_id' => request()->user()->id,
                'body' => request()->input('body')
            ]);
            $u->stars -= $price;
            $u->save();

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
        // never liked
        if (!PostCommentLike::where('user_id', auth()->id())->where('post_comment_id', $postcomment->id)->exists()) {
            PostCommentLike::create([
                'user_id' => auth()->user()->id,
                'post_comment_id' => $postcomment->id,
                'liked' => 1
            ]);
            // if it is not your own comment, give money to user
            if ($postcomment->author->id !== auth()->user()->id) {
                $postcomment->author->stars++;
                $postcomment->author->save();
            }
        } // record exists
        else {
            $postCommentLike = PostCommentLike::where('user_id', auth()->id())->where('post_comment_id', $postcomment->id);
            $isLiked = $postcomment->isLiked($postcomment);
            if (!$isLiked) {
                $postCommentLike->update(['liked' => 1]);
            } else {
                $postCommentLike->update(['liked' => 0]);
            }
        }
        return back();
    }
}
