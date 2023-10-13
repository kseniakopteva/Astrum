<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function explore()
    {
        return view('explore', [
            'posts' =>  Post::inRandomOrder()->latest()->filter(request(['search']))->paginate(50)->withQueryString()
        ]);
    }

    public function show(User $author, Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'body' => 'max:4000',
            'alt' => 'max:200'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = $this->make_slug($attributes['title']);
        $attributes['excerpt'] = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1',  $attributes['body']);

        $path = storage_path('app\public\images\\posts');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        Image::make($path . '\\' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '\\' . $imageName);

        $u = auth()->user();
        $price = 10;
        if ($u->stars >= $price) {
            Post::create($attributes);
            $u->stars -= $price;
            $u->save();
        }

        return back()
            ->with('success', 'You have successfully created a post!');
    }

    public function destroy(Request $request)
    {
        $post = Post::find($request->id);
        if (Auth::user()->id === $post->author->id) {
            $post->delete();
            return redirect('/profile');
        } else {
            return redirect()->route('explore');
        }
    }

    static public function make_slug(string $title)
    {
        $slug = strtolower(
            implode(
                '-',
                array_slice(
                    explode(
                        '-',
                        preg_replace(
                            '/[^a-zA-Z0-9-]/',
                            '-',
                            $title
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

        return $slug;
    }
}
