<?php

namespace App\Http\Controllers;

use App\Models\PostFrame;
use App\Models\ProfilePictureFrame;
use App\Models\Wallpaper;

class StarshopController extends Controller
{
    public function index()
    {
        return view('starshop.index', [
            'wallpapers' => Wallpaper::latest()->take(3)->get(),
            'profile_picture_frames' => ProfilePictureFrame::latest()->take(5)->get(),
            'post_frames' => PostFrame::latest()->take(5)->get(),
        ]);
    }
}
