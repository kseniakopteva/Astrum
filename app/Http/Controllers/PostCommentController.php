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

        $u = auth()->user();
        $price = 1;
        if ($u->stars >= $price) {
            $post->comments()->create([
                'user_id' => request()->user()->id,
                'body' => request()->input('body')
            ]);
            $u->stars -= $price;
            $u->save();
        }


        return back();
    }
}
