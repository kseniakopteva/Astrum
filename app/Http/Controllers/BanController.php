<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BanController extends Controller
{
    public function store(Request $request)
    {
        if (!(auth()->check() && auth()->user()->isModOrMore(auth()->user())))
            return redirect()->route('profile.index', $request->user_id)->with('success', 'You can\'t do that!');

        $attributes = $request->validate([
            'duration' => 'required',
            'reason' => 'required|max:1000',
            'user_id' => 'required|exists:users,id'
        ]);

        $now = Carbon::now()->timezone('Europe/Riga');
        $attributes['start_date'] = $now->toDateTimeString();

        if ($attributes['duration'] != 'forever') {
            $attributes['end_date'] = $now->addDays($attributes['duration'])->toDateTimeString();
        } else {
            $attributes['end_date'] = null;
        }

        unset($attributes['duration']);

        Ban::create($attributes);

        // resolve all reports for 'user' if a person gets banned
        Report::where('reported_type', 'user')->where('reported_id', $request->user_id)->update(['resolved' => 1]);

        return redirect()->route('profile.index', User::find($request->user_id)->username)->with('success', 'User banned!');
    }

    public function destroy(Request $request)
    {
        if (!(auth()->check() && auth()->user()->isModOrMore(auth()->user())))
            return redirect()->route('profile.index', $request->user_id)->with('success', 'You can\'t do that!');

        Ban::where('user_id', $request->user_id)->where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())->first()->delete();

        return redirect()->route('profile.index', User::find($request->user_id)->username)->with('success', 'User unbanned!');
    }
}
