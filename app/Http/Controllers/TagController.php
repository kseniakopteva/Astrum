<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Tag $tag, $page = null)
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

        if (request(['sort']) && in_array(request(['sort'])['sort'], ['all']))
            $items = $notes->merge($posts)->sortByDesc('created_at')->paginate(25, null, $page);
        elseif (request(['sort']) && in_array(request(['sort'])['sort'], ['notes']))
            $items = $notes->sortByDesc('created_at')->paginate(25, null, $page);
        elseif (request(['sort']) && in_array(request(['sort'])['sort'], ['posts']))
            $items = $posts->sortByDesc('created_at')->paginate(25, null, $page);

        return view('explore', [
            'items' =>  $items,
            'tag' => $tag
        ]);
    }
}
