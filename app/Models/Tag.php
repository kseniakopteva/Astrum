<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function wallpapers()
    {
        return $this->belongsToMany(Wallpaper::class);
    }

    public function profile_picture_frames()
    {
        return $this->belongsToMany(ProfilePictureFrame::class);
    }

    public function post_frames()
    {
        return $this->belongsToMany(PostFrame::class);
    }
}
