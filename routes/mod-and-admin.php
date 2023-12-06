<?php

use App\Http\Controllers\BanController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Models\Report;
use App\Models\User;

/* -------------------------------------------------------------------------- */
/*                            Admin and Mod routes                            */
/* -------------------------------------------------------------------------- */

Route::middleware('mod')->group(function () {
    Route::get('/mod/dashboard', function () {
        return view('mod-dashboard', [
            'reported_users' => Report::where('reported_type', 'user')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4),
            'reported_posts' => Report::where('reported_type', 'post')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4),
            'reported_post_comments' => Report::where('reported_type', 'post-comment')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4),
            'reported_notes' => Report::where('reported_type', 'note')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4),
            'reported_wallpapers' => Report::where('reported_type', 'wallpaper')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4),
            'reported_profile_picture_frames' => Report::where('reported_type', 'profile-picture-frame')->orderBy('resolved', 'DESC')->orderBy('created_at', 'ASC')->get()->take(4),
            'reported_post_frames' => Report::where('reported_type', 'post-frame')->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get()->take(4)
        ]);
    })->name('mod.dashboard');

    Route::get('/mod/dashboard/{type}', [ReportController::class, 'index'])->name('reports');

    Route::get('/mod/dashboard/report/{report}', function (Report $report) {
        return view('reports.show', [
            'report' => $report
        ]);
    })->name('report.show');

    Route::post('/make/creator', function () {
        if (auth()->user()?->role === 'admin') {
            $userToMakeMod = User::findOrFail(request('id'));
            if ($userToMakeMod->role === 'user') {
                $userToMakeMod->role = 'creator';
                $userToMakeMod->save();
            }
        }
        return back()->with('success', 'User ' . $userToMakeMod->username . ' is now a creator!');
    })->name('make.creator');

    Route::post('/ban/user', [BanController::class, 'store'])->name('ban');
    Route::post('/unban/user', [BanController::class, 'destroy'])->name('unban');
});

Route::middleware('admin')->group(function () {
    Route::post('/make/mod', function () {
        if (auth()->user()?->role === 'admin') {
            $userToMakeMod = User::findOrFail(request('id'));
            if ($userToMakeMod->role === 'creator') {
                $userToMakeMod->role = 'mod';
                $userToMakeMod->save();
            }
        }
        return back()->with('success', 'User ' . $userToMakeMod->username . ' is now a moderator!');
    })->name('make.mod');

    Route::post('/remove/mod', function () {
        if (auth()->user()?->role === 'admin') {
            $userToRemMod = User::findOrFail(request('id'));
            if ($userToRemMod->role === 'mod') {
                $userToRemMod->role = 'creator';
                $userToRemMod->save();
            }
        }
        return back()->with('success', 'User ' . $userToRemMod->username . ' is now NOT a moderator!');
    })->name('remove.mod');
});
