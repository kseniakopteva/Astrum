<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Tag $tag)
    {
        return view('posts.index', [
            'posts' =>  Post::latest()->whereHas('tags', fn ($q) => $q->where('tag_id', $tag->id))->paginate(10)
        ]);
    }
}
