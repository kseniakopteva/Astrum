<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUnfollowRequest;
use App\Models\User;

class FollowController extends Controller
{
    public function follow(FollowUnfollowRequest $request)
    {
        $userToFollow = User::findOrFail(request('id'));
        if ($userToFollow->id === auth()->user()->id)
            return back()->with('error', 'You can\'t follow yourself!');

        if (auth()->user()->isBlockedBy($userToFollow))
            return back();

        auth()->user()->follow($userToFollow);

        // if user reaches 30 followers, change his role to 'creator'
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

        return back()->with('success', 'You have unfollowed ' . $userToUnfollow->username . '!');
    }
}
