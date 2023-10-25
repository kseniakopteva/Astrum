<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePictureFrame extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'profile_picture_frame_likes')->where('liked', '=', '1');
    }

    public function isLiked($profile_picture_frame)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'profile_picture_frame_likes')
                ->where('profile_picture_frame_likes.user_id', '=', auth()->user()->id)
                ->where('profile_picture_frame_likes.profile_picture_frame_id', '=', $profile_picture_frame->id)
                ->where('profile_picture_frame_likes.liked', '=', 1)
                ->exists();
        else return null;
    }
}
