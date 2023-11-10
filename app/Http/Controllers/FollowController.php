<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUnfollowRequest;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(FollowUnfollowRequest $request)
    {
        // dd('Follow!');
        $userToFollow = User::findOrFail(request('id'));
        if ($userToFollow->id === auth()->user()->id)
            return back()->with('success', 'You can\'t follow yourself!');

        if (auth()->user()->isBlockedBy($userToFollow))
            return back();

        auth()->user()->follow($userToFollow);

        if ($userToFollow->followers->count() == 30 && $userToFollow->role == 'user') {
            $userToFollow->role = 'creator';
            $userToFollow->save();
        }

        return back()->with('success', 'You have followed ' . $userToFollow->username . '!');
    }

    public function unfollow(FollowUnfollowRequest $request)
    {
        $userToUnfollow = User::findOrFail(request('id'));
        auth()->user()->unfollow($userToUnfollow);

        return back()->with('success', 'You have unfollowed ' . $userToUnfollow->username . '.');
    }
}
