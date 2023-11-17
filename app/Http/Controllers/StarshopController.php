<?php

namespace App\Http\Controllers;

use App\Models\PostFrame;
use App\Models\ProfilePictureFrame;
use App\Models\User;
use App\Models\Wallpaper;

class StarshopController extends Controller
{
    public function index()
    {
        $banned_users = User::getBannedUserIds();

        return view('starshop.index', [
            'wallpapers' => Wallpaper::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(3)->get(),
            'profile_picture_frames' => ProfilePictureFrame::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(5)->get(),
            'post_frames' => PostFrame::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(4)->get(),
        ]);
    }
}
