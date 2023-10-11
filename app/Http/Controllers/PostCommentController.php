<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostCommentController extends Controller
{
    public function store(Post $post)
    {
        request()->validate([
            'body' => 'required|max:4000'
        ]);

        $post->comments()->create([
            'user_id' => request()->user()->id,
            'body' => request()->input('body')
        ]);

        return back();
    }
}
