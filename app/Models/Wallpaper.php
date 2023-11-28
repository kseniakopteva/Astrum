<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class);
    // }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'wallpaper_likes')->where('liked', '=', '1');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }

    public function isLiked($wallpaper)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'wallpaper_likes')
                ->where('wallpaper_likes.user_id', '=', auth()->user()->id)
                ->where('wallpaper_likes.wallpaper_id', '=', $wallpaper->id)
                ->where('wallpaper_likes.liked', '=', 1)
                ->exists();
        else return null;
    }

    public function reports(Wallpaper $wp)
    {
        return Report::where('reported_type', 'wallpaper')->where('reported_id', $wp->id)->latest()->get();
    }
}
