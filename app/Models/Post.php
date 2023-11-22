<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $with = ['tags', 'author'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn ($query, $search) =>
        $query
            ->where('title', 'like', '%' . $search . '%')
            ->orWhere('body', 'like', '%' . $search . '%'));
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function post_frame()
    {
        return $this->belongsTo(PostFrame::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes')->where('liked', '=', '1');
    }

    public function isLiked($post)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'post_likes')
                ->where('post_likes.user_id', '=', auth()->user()->id)
                ->where('post_likes.post_id', '=', $post->id)
                ->where('post_likes.liked', '=', 1)
                ->exists();
        else return null;
    }
    public function reports()
    {
        return Report::where('reported_type', 'post')->where('reported_id', $this->id)->latest()->get();
    }
}
