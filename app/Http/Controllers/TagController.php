<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Tag $tag)
    {
        $posts = Post::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds())->whereHas('tags', fn ($q) => $q->where('tag_id', $tag->id));
        if (auth()->check())
            $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());
        $posts = $posts->latest()->filter(request(['search']))->get();

        $notes = Note::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds())->whereHas('tags', fn ($q) => $q->where('tag_id', $tag->id));
        if (auth()->check())
            $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());
        $notes = $notes->latest()
            ->filter(request(['search']))->get();

        $items = $notes->merge($posts)->sortByDesc('created_at');

        return view('explore', [
            'items' =>  $items,
            'tag' => $tag
        ]);
    }
}
