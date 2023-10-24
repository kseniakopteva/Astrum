<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Note extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class);
    // }

    public function comments()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'note_likes')->where('liked', '=', '1');
    }

    public function isLiked($note)
    {
        if (auth()->check())
            return $this
                ->belongsToMany(User::class, 'note_likes')
                ->where('note_likes.user_id', '=', auth()->user()->id)
                ->where('note_likes.note_id', '=', $note->id)
                ->where('note_likes.liked', '=', 1)
                ->exists();
        else return null;
    }
}
