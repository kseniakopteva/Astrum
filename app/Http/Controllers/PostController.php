<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', [
            'posts' =>  Post::latest()->filter(request(['search']))->paginate(10)->withQueryString()
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => '',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'body' => '',
        ]);
        // if ($attributes->fails()) {
        //     return back()->withErrors($attributes);
        // }
        // dd($attributes);
        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = preg_replace("#[[:punct:]]#", "", str_replace(" ", "-", $attributes['title']));
        $attributes['excerpt'] = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1',  $attributes['body']);

        $imageName = $request->user()->username . '.' . time() . '.' . $request->image->extension();

        $attributes['image'] = $imageName;

        $request->image->move(public_path('images'), $imageName);

        /*
            Write Code Here for
            Store $imageName name in DATABASE from HERE
        */

        Post::create($attributes);

        return back()
            ->with('success', 'You have successfully upload image.');
    }
}
