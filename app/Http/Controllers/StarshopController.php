<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\PostFrame;
use App\Models\ProfilePictureFrame;
use App\Models\Wallpaper;
use Carbon\Carbon;

class StarshopController extends Controller
{
    public function index()
    {
        $banned_users = Ban::where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())->pluck('user_id');

        return view('starshop.index', [
            'wallpapers' => Wallpaper::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(3)->get(),
            'profile_picture_frames' => ProfilePictureFrame::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(5)->get(),
            'post_frames' => PostFrame::where('removed', false)->whereNotIn('user_id', $banned_users)->latest()->take(5)->get(),
        ]);
    }
}
