<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StarshopController;
use App\Http\Controllers\WallpaperController;
use App\Http\Controllers\ProfilePictureFrameController;
use App\Http\Controllers\PostFrameController;
use App\Http\Controllers\ColourController;

/* -------------------------------------------------------------------------- */
/*                               Starshop Routes                              */
/* -------------------------------------------------------------------------- */

Route::get('/starshop', [StarshopController::class, 'redirect'])->name('starshop')->middleware('guest');

Route::middleware('auth')->group(function () {

    /* ---------------------------------- Index --------------------------------- */
    Route::get('/starshop', [StarshopController::class, 'index'])->name('starshop');

    /* -------------------------------- Wallpaper ------------------------------- */
    Route::get('/starshop/wallpapers/{wallpaper:slug}', [WallpaperController::class, 'show'])->name('starshop.wallpapers.show');
    Route::get('/starshop/wallpapers', [WallpaperController::class, 'index'])->name('starshop.wallpapers');
    Route::post('/starshop/wallpapers/store', [WallpaperController::class, 'store'])->name('starshop.wallpapers.store');
    Route::post('/starshop/wallpapers/delete', [WallpaperController::class, 'destroy'])->name('wallpaper.delete');

    /* -------------------------- Profile Picture Frame ------------------------- */
    Route::get('/starshop/profile-picture-frames/{profile_picture_frame:slug}', [ProfilePictureFrameController::class, 'show'])->name('starshop.profile-picture-frames.show');
    Route::get('/starshop/profile-picture-frames', [ProfilePictureFrameController::class, 'index'])->name('starshop.profile-picture-frames');
    Route::post('/starshop/profile-picture-frames/store', [ProfilePictureFrameController::class, 'store'])->name('starshop.profile-picture-frames.store');
    Route::post('/starshop/profile-picture-frames/delete', [ProfilePictureFrameController::class, 'destroy'])->name('profile-picture-frame.delete');

    /* ------------------------------- Post Frames ------------------------------ */
    Route::get('/starshop/post-frames/{post_frame:slug}', [PostFrameController::class, 'show'])->name('starshop.post-frames.show');
    Route::get('/starshop/post-frames', [PostFrameController::class, 'index'])->name('starshop.post-frames');
    Route::post('/starshop/post-frames/store', [PostFrameController::class, 'store'])->name('starshop.post-frames.store');
    Route::post('/starshop/post-frames/delete', [PostFrameController::class, 'destroy'])->name('post-frame.delete');

    /* --------------------------------- Colours -------------------------------- */
    Route::get('/starshop/colours', [ColourController::class, 'index'])->name('starshop.colours');

    /* ------------------------------- Like routes ------------------------------ */
    Route::post('/starshop/wallpapers/{wallpaper}/like', [WallpaperController::class, 'toggleLike'])->name('wallpaper.like');
    Route::post('/starshop/profile-picture-frames/{profile_picture_frame}/like', [ProfilePictureFrameController::class, 'toggleLike'])->name('profile-picture-frame.like');
    Route::post('/starshop/post-frames/{post_frame}/like', [PostFrameController::class, 'toggleLike'])->name('post-frame.like');

    /* ------------------------------- Buy routes ------------------------------- */
    Route::post('/starshop/wallpapers/buy', [WallpaperController::class, 'buy'])->name('starshop.wallpapers.buy');
    Route::post('/starshop/profile-picture-frames/buy', [ProfilePictureFrameController::class, 'buy'])->name('starshop.profile-picture-frames.buy');
    Route::post('/starshop/colours/buy', [ColourController::class, 'buy'])->name('starshop.colours.buy');
    Route::post('/starshop/post-frames/buy', [PostFrameController::class, 'buy'])->name('starshop.post-frames.buy');
});

/* --------------------------------- Create --------------------------------- */
Route::middleware('creator')->group(function () {
    Route::get('/starshop/wallpapers/create', [WallpaperController::class, 'create'])->name('starshop.wallpapers.create');
    Route::get('/starshop/profile-picture-frames/create', [ProfilePictureFrameController::class, 'create'])->name('starshop.profile-picture-frames.create');
    Route::get('/starshop/post-frames/create', [PostFrameController::class, 'create'])->name('starshop.post-frames.create');
});
