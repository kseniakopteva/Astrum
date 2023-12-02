<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Note;
use App\Models\Post;
use App\Models\PostFrame;
use App\Models\PostLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function explore($page = null)
    {
        // dd(request());
        if (request(['search']) && is_null(request(['search'])['search']))
            return redirect('/explore?' . http_build_query(request()->except('search')));

        if (request(['search']) && !is_null(request(['search'])['search'])) {
            $users = User::whereNotIn('id', User::getBannedUserIds());
            if (auth()->check())
                $users = $users->whereNotIn('id', auth()->user()->allBlockedBy());
            $users = $users->latest()
                ->filter(request(['search']))->get();

            //     $posts = Post::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            //     if (auth()->check())
            //         $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());
            //     $posts = $posts->latest()
            //         ->filter(request(['search']))->get();

            //     $notes = Note::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            //     if (auth()->check())
            //         $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());
            //     $notes = $notes->latest()
            //         ->filter(request(['search']))->get();

            //     $items = $notes->merge($posts)->sortByDesc('created_at')->paginate(25, null, $page);

            //     return view('explore', [
            //         'users' => $users,
            //         'items' => $items
            //     ]);
        } else {
            $users = null;
        }

        if (!request(['sort']) || request(['sort'])['sort'] == 'all') {
            //
            //
            $posts = Post::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            if (auth()->check())
                $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());
            $posts = $posts->latest()
                ->filter(request(['search']))->get();

            $notes = Note::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            if (auth()->check())
                $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());
            $notes = $notes->latest()
                ->filter(request(['search']))->get();

            $items = new Collection();
            $items = $items->concat($posts)->concat($notes)->sortByDesc('created_at')->paginate(25, null, $page);

            return view('explore', [
                'items' => $items,
                'users' => $users
            ]);
            //
            //
        } else if (request(['sort'])['sort'] == 'posts') {
            //
            //
            $posts = Post::where('removed', false)
                ->whereNotIn('user_id', User::getBannedUserIds());

            if (auth()->check())
                $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());

            $posts = $posts->latest()
                ->filter(request(['search']))
                ->paginate(30)
                ->withQueryString();

            return view('explore', [
                'items' => $posts,
                'users' => $users
            ]);
            //
            //
        } else if (request(['sort'])['sort'] == 'notes') {
            $notes = Note::where('removed', false)
                ->whereNotIn('user_id', User::getBannedUserIds());

            if (auth()->check())
                $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());

            $notes = $notes->latest()
                ->filter(request(['search']))
                ->paginate(30)
                ->withQueryString();

            return view('explore', [
                'items' => $notes,
                'users' => $users
            ]);
        }
        // else if (request(['sort'])['sort'] == 'week') {
        //
        //

        //
        //
        // } else if (request(['sort'])['sort'] == 'month') {
        // } else if (request(['sort'])['sort'] == 'year') {
        // }
    }

    public function show(User $author, Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t post because you are banned.');

        $u = auth()->user();
        $price = 10;
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes = $request->validate([
            'title' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
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

        $path = public_path('images\\posts');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);

        $image = Image::make($path . '/' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        if (!is_null($request->watermark) && $request->watermark != 'none') {
            switch ($request->watermark) {
                case 'bottom-right':
                    $image->text('@' . auth()->user()->username, $image->width() - strlen(auth()->user()->username) * 1.8 - 10, $image->height() - 38, function ($font) use ($image, $request) {
                        $font->file(public_path('fonts/Sono-Medium.ttf'));
                        $font->size($image->height() / 25);
                        $font->color($request->watermark_color);
                        $font->align('right');
                        $font->valign('center');
                    });
                    break;
                case 'center':
                    $image->text('@' . auth()->user()->username, $image->width() / 2 - strlen(auth()->user()->username) / 2 * 8.5, $image->height() / 2, function ($font) use ($image, $request) {
                        $font->file(public_path('fonts/Sono-Medium.ttf'));
                        $font->size($image->height() / 25);
                        $font->color($request->watermark_color);
                        $font->align('center');
                        $font->valign('center');
                    });
                    break;
                case 'tiled':
                    if ($image->width() > $image->height()) {
                        $gap = $image->height() / 3;
                    } else {
                        $gap = $image->width() / 3;
                    }

                    $x = $image->height() / 7;
                    while ($x < $image->width()) {
                        $y = $image->height() / 8;
                        while ($y < $image->height()) {
                            $image->text('@' . auth()->user()->username, $x, $y, function ($font) use ($image, $request) {
                                $font->file(public_path('fonts/Sono-Medium.ttf'));
                                $font->size($image->height() / 25);
                                $font->angle(30);
                                $font->color($request->watermark_color);
                                $font->align('center');
                                $font->valign('center');
                            });
                            $y += $gap;
                        }
                        $x += $gap;
                    }
                    break;
            }
        }

        $image->save($path . '/' . $imageName);

        $new_post = Post::create($attributes);
        $u->stars -= $price;
        $u->save();

        $tags = array_filter(array_map('trim', explode(',', $request['tags'])));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag], ['slug' => str_replace(' ', '_', $tag)])->save();
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
            unlink(public_path('images/posts/' . $post->image));
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
