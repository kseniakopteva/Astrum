<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }


    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_comment_likes')->where('liked', '=', '1');
    }

    public function isLiked($comment)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'post_comment_likes')
                ->where('post_comment_likes.user_id', '=', auth()->user()->id)
                ->where('post_comment_likes.post_comment_id', '=', $comment->id)
                ->where('post_comment_likes.liked', '=', 1)
                ->exists();
        else return null;
    }

    public function reports()
    {
        return Report::where('reported_type', 'post-comment')->where('reported_id', $this->id)->latest()->get();
    }
}
