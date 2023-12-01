<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Block;
use App\Models\Note;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostFrame;
use App\Models\Product;
use App\Models\ProfilePictureFrame;
use App\Models\Report;
use App\Models\User;
use App\Models\Wallpaper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BanController extends Controller
{
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        if (!(auth()->check() && auth()->user()->isModOrMore()))
            return redirect()->route('profile.index', $user->username)->with('error', 'You can\'t do that!');

        if (!is_null($user->getCurrentBan()))
            return redirect()->route('profile.index', $user->username)->with('error', 'This user is already banned.');

        $attributes = $request->validate([
            'duration' => 'required',
            'reason' => 'required|max:1000',
            'user_id' => 'required|exists:users,id'
        ]);


        $attributes['banned_by_id'] = auth()->user()->id;

        $now = Carbon::now()->timezone('Europe/Riga');
        $attributes['start_date'] = $now->toDateTimeString();

        if ($attributes['duration'] != 'forever') {
            $attributes['end_date'] = $now->addDays($attributes['duration'])->toDateTimeString();
        } else {
            $attributes['end_date'] = null;

            // remove all posts, notes, comments, wallpapers, pf, ppf
            Post::where('user_id', $user->id)->update(['removed' => 1]);
            Note::where('user_id', $user->id)->update(['removed' => 1]);
            PostComment::where('user_id', $user->id)->update(['removed' => 1]);
            Product::where('user_id', $user->id)->update(['removed' => 1]);
            Wallpaper::where('user_id', $user->id)->update(['removed' => 1]);
            ProfilePictureFrame::where('user_id', $user->id)->update(['removed' => 1]);
            PostFrame::where('user_id', $user->id)->update(['removed' => 1]);
        }

        unset($attributes['duration']);

        Ban::create($attributes);

        // resolve all reports for 'user' if a person gets banned
        Report::where('reported_type', 'user')->where('reported_id', $request->user_id)->update(['resolved' => 1]);

        return redirect()->route('profile.index', $user->username)->with('success', 'User banned!');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);

        if (!(auth()->check() && auth()->user()->isModOrMore()))
            return redirect()->route('profile.index', $user->username)->with('error', 'You can\'t do that!');

        $user->getCurrentBan()->delete();

        return redirect()->route('profile.index', User::find($request->user_id)->username)->with('success', 'User unbanned!');
    }

    public function block_store(Request $request)
    {
        $user = auth()->user();
        $blocked = User::find($request->user_id);

        if (!auth()->check() || auth()->user()->id == $request->user_id)
            return redirect()->route('profile.index', $blocked->username)->with('error', 'You can\'t do that!');


        if ($blocked->isBlockedBy($user))
            return redirect()->route('profile.index', $blocked->username)->with('error', 'You have already blocked ' . $blocked->username . '!');

        if ($user->isFollowing($blocked))
            $user->unfollow($blocked);

        if ($blocked->isFollowing($user))
            $blocked->unfollow($user);

        Block::create([
            'user_id' => $user->id,
            'blocked_id' => $blocked->id
        ]);

        return redirect()->route('profile.index', $blocked->username)->with('success', 'User ' . $blocked->username . ' is blocked');
    }

    public function block_destroy(Request $request)
    {
        $user = User::find($request->user_id);

        if (!(auth()->check() && auth()->user()->id != $request->user_id))
            return redirect()->route('profile.index', $user->username)->with('error', 'You can\'t do that!');

        Block::where('user_id', auth()->user()->id)->where('blocked_id', $user->id)->delete();

        return redirect()->route('profile.index', $user->username)->with('success', 'User unblocked!');
    }
}
