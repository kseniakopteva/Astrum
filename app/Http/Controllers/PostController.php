<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostFrame;
use App\Models\PostLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function explore()
    {
        $banned_users = User::getBannedUserIds();

        $posts = Post::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->filter(request(['search']))->paginate(100)->withQueryString();
        return view('explore', [
            'posts' => $posts
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
        if (auth()->user()->isBanned(auth()->user()))
            return back()->with('success', 'You can\'t post because you are banned.');

        $u = auth()->user();
        $price = 10;
        if ($u->stars < $price) {
            return back()
                ->with('success', 'You don\'t have enough money!');
        }

        $attributes = $request->validate([
            'title' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'body' => 'max:4000',
            'alt' => 'max:200'
        ]);

        if (!is_null($request->frame)) {
            if ($request->frame[0] != 'n') {
                // add a post frame of id to this post.
                $pf = PostFrame::find($request->frame[0]);

                $existing = auth()->user()->ownedPostFrames()
                    ->where('post_frame_id', $pf->id)
                    ->where('post_frame_user.user_id', auth()->user()->id)->first();
                $new_amount = $existing->pivot->amount - 1;
                if ($new_amount != 0)
                    auth()->user()->ownedPostFrames()->updateExistingPivot($pf->id, ['amount' => $new_amount]);
                else
                    auth()->user()->ownedPostFrames()->detach($pf->id);

                $attributes['post_frame_id'] = $request->frame[0];
            }
        }

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

        $new_post = Post::create($attributes);
        $u->stars -= $price;
        $u->save();

        $tags = array_filter(array_map('trim', explode(',', $request['tags'])));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag, 'slug' => str_replace(' ', '_', $tag)])->save();
        }
        $tags = Tag::whereIn('name', $tags)->get()->pluck('id');
        $new_post->tags()->sync($tags);

        return redirect()->route('post.show', ['author' => auth()->user()->username, 'post' => $new_post->slug])
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





    public function toggleLike(Post $post)
    {
        // never liked
        if (!PostLike::where('user_id', auth()->id())->where('post_id', $post->id)->exists()) {
            PostLike::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'liked' => 1
            ]);
            // if it is not your own post, give money to user
            if ($post->author->id !== auth()->user()->id) {
                $post->author->stars += 2;
                $post->author->save();
            }
        } // record exists
        else {
            $postLike = PostLike::where('user_id', auth()->id())->where('post_id', $post->id);
            $isLiked = $post->isLiked($post);
            if (!$isLiked) {
                $postLike->update(['liked' => 1]);
            } else {
                $postLike->update(['liked' => 0]);
            }
        }
        // return back();
        return Redirect::to(URL::previous() . "#" . $post->slug);
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
