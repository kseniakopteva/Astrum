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
        auth()->user()->follow($userToFollow);

        // return response()->noContent(200);

        return back()->with('success', 'You have followed ' . $userToFollow->username . '!');
    }

    public function unfollow(FollowUnfollowRequest $request)
    {
        $userToUnfollow = User::findOrFail(request('id'));
        auth()->user()->unfollow($userToUnfollow);

        // return response()->noContent(200);

        return back()->with('success', 'You have unfollowed ' . $userToUnfollow->username . '.');
    }
}
