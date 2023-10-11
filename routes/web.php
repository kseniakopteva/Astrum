<?php

use App\Http\Controllers\FAQuestionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NoteCommentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StarshopController;
use App\Http\Controllers\TagController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/* -------------------------------------------------------------------------- */
/*                              Auth User Profile                             */
/* -------------------------------------------------------------------------- */

Route::get('/profile', function () {
    return view('profile.index', [
        'user' => auth()->user(),
        'posts' => Post::latest()->whereHas('author', fn ($q) => $q->where('user_id', auth()->user()->id))->paginate(20),
        'followers' => auth()->user()->followers,
        'following' => auth()->user()->following,
    ]);
})->middleware(['auth'])->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/settings/remove', [ProfileController::class, 'removeImage']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/* -------------------------------------------------------------------------- */
/*                             Other User Profile                             */
/* -------------------------------------------------------------------------- */

Route::middleware('auth')->group(function () {
    Route::post("/follow", [FollowController::class, 'follow'])->name('user.follow');
    Route::post("/unfollow", [FollowController::class, 'unfollow'])->name('user.unfollow');
});

Route::get('/u/{author:username}', [ProfileController::class, 'index']);

Route::get('/u/{author:username}/posts', [ProfileController::class, 'posts']);
Route::get('/u/{author:username}/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/u/{author:username}/notes', [ProfileController::class, 'notes']);
Route::get('/u/{author:username}/notes/{note:slug}', [NoteController::class, 'show']);
Route::get('/u/{author:username}/shop', [ProfileController::class, 'shop']);
Route::get('/u/{author:username}/faq', [ProfileController::class, 'faq']);
Route::get('/u/{author:username}/about', [ProfileController::class, 'about']);

Route::get('/', function () {
    return view('feed', [
        'posts' => Post::latest()->get()
    ]);
})->name('feed');


Route::post('/posts/{post:slug}/comments', [PostCommentController::class, 'store']);
Route::post('/notes/{note:slug}/comments', [NoteCommentController::class, 'store']);
Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
Route::post('/note/store', [NoteController::class, 'store'])->name('note.store');

Route::post('/post/delete', [PostController::class, 'destroy'])->name('post.delete');
Route::post('/note/delete', [NoteController::class, 'destroy'])->name('note.delete');

Route::get('/tags/{tag:slug}', [TagController::class, 'index']);


Route::post('/u/{user:id}/faq', [FAQuestionController::class, 'store']);



Route::get('/explore', [PostController::class, 'explore'])->name('explore');

/* -------------------------------------------------------------------------- */
/*                                  StarShop                                  */
/* -------------------------------------------------------------------------- */

Route::middleware('creator')->group(function () {
    Route::get('/starshop/wallpapers/create', [StarshopController::class, 'wallpapers_create'])->name('starshop.wallpapers.create');
    Route::get('/starshop/profile-picture-frames/create', [StarshopController::class, 'profile_picture_frames_create'])->name('starshop.profile-picture-frames.create');
    Route::get('/starshop/post-frames/create', [StarshopController::class, 'post_frames_create'])->name('starshop.post-frames.create');
});

Route::get('/starshop', [StarshopController::class, 'redirect'])->name('starshop')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/starshop', [StarshopController::class, 'index'])->name('starshop');

    Route::get('/starshop/wallpapers', [StarshopController::class, 'wallpapers_index'])->name('starshop.wallpapers');
    Route::post('/starshop/wallpapers/store', [StarshopController::class, 'wallpapers_store'])->name('starshop.wallpapers.store');

    Route::get('/starshop/profile-picture-frames', [StarshopController::class, 'profile_picture_frames_index'])->name('starshop.profile-picture-frames');
    Route::post('/starshop/profile-picture-frames/store', [StarshopController::class, 'profile_picture_frames_store'])->name('starshop.profile-picture-frames.store');

    Route::get('/starshop/post-frames', [StarshopController::class, 'post_frames_index'])->name('starshop.post-frames');
    Route::post('/starshop/post-frames/store', [StarshopController::class, 'post_frames_store'])->name('starshop.post-frames.store');
});

Route::get('/help', function () {
    return view('help');
})->name('help');

/* -------------------------------------------------------------------------- */
/*                                Admin And Mod                               */
/* -------------------------------------------------------------------------- */

// Route::get('/admin/dashboard', function () {
//     return view('admin-dashboard');
// })->name('admin.dashboard')->middleware('admin');

Route::get('/mod/dashboard', function () {
    return view('mod-dashboard');
})->name('mod.dashboard')->middleware('mod');

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

// Route::post('/make/admin', function () {
//     if (auth()->user()?->role === 'admin') {
//         $userToMakeAdmin = User::findOrFail(request('id'));
//         if (in_array($userToMakeAdmin->role, ['creator', 'mod'])) {
//             $userToMakeAdmin->role = 'admin';
//             $userToMakeAdmin->save();
//         }
//     }
//     return back()->with('success', 'User ' . $userToMakeAdmin->username . ' is now an administrator!');
// })->name('make.admin');

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

Route::post('/ban/{user}', function (User $user) {
    // TODO ban
})->name('ban');

Route::post('/report/{user}', function (User $user) {
    // TODO report
})->name('report');

Route::post('/block/{user}', function (User $user) {
    // TODO block
})->name('block');
