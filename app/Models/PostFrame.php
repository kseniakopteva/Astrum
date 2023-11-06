<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFrame extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_frame_likes')->where('liked', '=', '1');
    }

    public function isLiked($post_frame)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'post_frame_likes')
                ->where('post_frame_likes.user_id', '=', auth()->user()->id)
                ->where('post_frame_likes.wallpaper_id', '=', $post_frame->id)
                ->where('post_frame_likes.liked', '=', 1)
                ->exists();
        else return null;
    }

    public function reports(PostFrame $pf)
    {
        return Report::where('reported_type', 'post-frame')->where('reported_id', $pf->id)->latest()->get();
    }
}
