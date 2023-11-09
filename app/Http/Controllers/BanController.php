<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Note;
use App\Models\Post;
use App\Models\PostFrame;
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
        if (!(auth()->check() && auth()->user()->isModOrMore(auth()->user())))
            return redirect()->route('profile.index', $request->user_id)->with('success', 'You can\'t do that!');

        $user = User::find($request->user_id);
        if (!is_null($user->getCurrentBan($user)))
            return redirect()->route('profile.index', $request->user_id)->with('success', 'This user is already banned.');

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
        if (!(auth()->check() && auth()->user()->isModOrMore(auth()->user())))
            return redirect()->route('profile.index', $request->user_id)->with('success', 'You can\'t do that!');

        $user = User::find($request->user_id);
        $user->getCurrentBan($user)->delete();

        return redirect()->route('profile.index', User::find($request->user_id)->username)->with('success', 'User unbanned!');
    }
}
