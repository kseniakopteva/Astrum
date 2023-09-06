<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('explore', [
            'posts' =>  Post::latest()->filter(request(['search']))->paginate(10)->withQueryString()
        ]);
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'body' => 'max:4000',
            'alt' => 'max:200'
        ]);

        $attributes['user_id'] = auth()->user()->id;

        $slug = strtolower(
            implode(
                '-',
                array_slice(
                    explode(
                        '-',
                        preg_replace(
                            '/[^a-zA-Z0-9-]/',
                            '-',
                            $attributes['title']
                        )
                    ),
                    0,
                    7
                )
            )
        );

        if (strlen($slug) > 50) {
            $slug = substr($slug, 0, -50);
        }
        $slug = $slug  . '-' . time();
        $slug = trim(preg_replace('/-+/', '-', $slug), '-');

        $attributes['slug'] = $slug;


        $attributes['excerpt'] = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1',  $attributes['body']);

        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();

        $attributes['image'] = $imageName;

        $request->image->move(public_path('images'), $imageName);

        /*
            Write Code Here for
            Store $imageName name in DATABASE from HERE
        */

        Post::create($attributes);

        return back()
            ->with('success', 'You have successfully created a post!');
    }
}
