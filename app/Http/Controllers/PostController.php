<?php

namespace App\Http\Controllers;

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
    public function feed($page = null)
    {
        if (!auth()->check())
            return redirect()->route('explore')->with('error', 'Log in to access your feed!');

        // get all users, whom the current user is following, who are not banned, and add the current user if not banned
        $userIds = auth()->user()->following()->whereNotIn('users.id', User::getBannedUserIds())->pluck('follows.following_id');
        if (!auth()->user()->isBanned())
            $userIds[] = auth()->user()->id;

        // get posts and notes by these users
        $posts = Post::whereIn('user_id', $userIds)->get();
        $notes = Note::whereIn('user_id', $userIds)->get();

        // merge posts and notes
        $items = new Collection();
        $items = $items->concat($posts)->concat($notes)->sortByDesc('created_at')->paginate(30, null, $page);

        return view('feed', [
            'items' => $items
        ]);
    }

    public function explore($page = null)
    {
        // if search exists in query, but it is null, redirect to the same url but without null search in it
        if (request(['search']) && is_null(request(['search'])['search']))
            return redirect('/explore?' . http_build_query(request()->except('search')));

        // set the 'users' prop based on the search query
        if (request(['search']) && !is_null(request(['search'])['search'])) {
            $users = User::whereNotIn('id', User::getBannedUserIds());
            if (auth()->check())
                $users = $users->whereNotIn('id', auth()->user()->allBlockedBy());
            $users = $users->latest()
                ->filter(request(['search']))->get();
        } else {
            $users = null;
        }

        // return different 'items' prop based on the sort
        if (!request(['sort']) || request(['sort'])['sort'] == 'all') {
            $posts = Post::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            if (auth()->check())
                $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());
            $posts = $posts->latest()
                ->filter(request(['search']))->get();

            $notes = Note::where('removed', false)->whereNotIn('user_id', User::getBannedUserIds());
            if (auth()->check())
                $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());
            $notes = $notes->latest()->filter(request(['search']))->get();

            // merging posts and notes
            $items = new Collection();
            $items = $items->concat($posts)->concat($notes)->sortByDesc('created_at')->paginate(25, null, $page);

            return view('explore', [
                'items' => $items,
                'users' => $users
            ]);
        } else if (request(['sort'])['sort'] == 'posts') {
            $posts = Post::where('removed', false)
                ->whereNotIn('user_id', User::getBannedUserIds());

            if (auth()->check())
                $posts = $posts->whereNotIn('user_id', auth()->user()->allBlockedBy());

            $posts = $posts->latest()->filter(request(['search']))->get();

            // creating new merged collection containing only posts for a custom pagination to work
            $items = new Collection();
            $items = $items->concat($posts)->sortByDesc('created_at')->paginate(30, null, $page);

            return view('explore', [
                'items' => $items,
                'users' => $users
            ]);
        } else if (request(['sort'])['sort'] == 'notes') {
            $notes = Note::where('removed', false)
                ->whereNotIn('user_id', User::getBannedUserIds());

            if (auth()->check())
                $notes = $notes->whereNotIn('user_id', auth()->user()->allBlockedBy());

            $notes = $notes->latest()->filter(request(['search']))->get();

            // creating new merged collection containing only notes for a custom pagination to work
            $items = new Collection();
            $items = $items->concat($notes)->sortByDesc('created_at')->paginate(30, null, $page);

            return view('explore', [
                'items' => $items,
                'users' => $users
            ]);
        }
    }

    public function show(User $author, Post $post)
    {
        if (!$post->removed)
            return view('posts.show', [
                'post' => $post
            ]);
        else return redirect()->route('explore');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t post because you are banned.');

        // price to make a post is 10 stars
        $price = 10;
        $u = auth()->user();
        if ($u->stars < $price) {
            return back()
                ->with('error', 'You don\'t have enough money!');
        }

        $attributes = $request->validate([
            'title' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'body' => 'max:4000',
            'alt' => 'max:200',
            'tags' => 'max:500'
        ]);

        unset($attributes['tags']);

        // if user chose a post frame, add it
        if (!is_null($request->frame) && $request->frame[0] != 'n') {

            $pf = PostFrame::find($request->frame[0]);

            // change the amount of this post frame
            $existing = auth()->user()->ownedPostFrames()
                ->where('post_frame_id', $pf->id)
                ->where('post_frame_user.user_id', auth()->user()->id)->first();
            $new_amount = $existing->pivot->amount - 1;

            // if amount becomes 0, remove the db record altogether
            if ($new_amount != 0)
                auth()->user()->ownedPostFrames()->updateExistingPivot($pf->id, ['amount' => $new_amount]);
            else
                auth()->user()->ownedPostFrames()->detach($pf->id);

            $attributes['post_frame_id'] = $request->frame[0];
        }

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = $this->make_slug($attributes['title']);
        $attributes['excerpt'] = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1',  $attributes['body']);

        // saving image, resizing it without making it larger than it is, saving the aspect ratio
        $path = public_path('images\\posts');
        $imageName = strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();
        $attributes['image'] = $imageName;
        $request->image->move($path, $imageName);
        $image = Image::make($path . '/' . $imageName)->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // if user chose a watermark, generate the according watermark on the existing image
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

        // creating or using existing tags, syncing
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
            // before deleting the post, remove the image from the storage
            unlink(public_path('images/posts/' . $post->image));
            $post->delete();
            return redirect('/profile');
        } else {
            return redirect()->route('explore');
        }
    }

    public function toggleLike(Post $post)
    {
        // if user has never liked the post, create new db row
        if (!PostLike::where('user_id', auth()->id())->where('post_id', $post->id)->exists()) {
            PostLike::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'liked' => 1
            ]);
            // if it is not this user's post, give money (2 star) to the user
            if ($post->author->id !== auth()->user()->id) {
                $post->author->stars += 2;
                $post->author->save();
            }
        } // if user has liked this post before (record exists)
        else {
            $postLike = PostLike::where('user_id', auth()->id())->where('post_id', $post->id);
            // check if the existing record says the post is liked or not, toggle it
            $isLiked = $post->isLiked($post);
            if (!$isLiked) {
                $postLike->update(['liked' => 1]);
            } else {
                $postLike->update(['liked' => 0]);
            }
        }

        // if post is in a feed, it will anchor to it on return
        return Redirect::to(URL::previous() . "#" . $post->slug);
    }

    static public function make_slug(string $title)
    {
        $slug = strtolower(implode('-', array_slice(explode('-', preg_replace('/[^a-zA-Z0-9-]/', '-', $title)), 0, 7)));

        if (strlen($slug) > 50) {
            $slug = substr($slug, 0, -50);
        }
        $slug = $slug  . '-' . time();
        $slug = trim(preg_replace('/-+/', '-', $slug), '-');

        return $slug;
    }
}
