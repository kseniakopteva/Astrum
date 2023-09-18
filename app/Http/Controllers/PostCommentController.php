<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
