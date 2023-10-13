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
}
