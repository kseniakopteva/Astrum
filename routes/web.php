<?php

use App\Http\Controllers\ColourController;
use App\Http\Controllers\FAQuestionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFrameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePictureFrameController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StarshopController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WallpaperController;
use App\Models\Note;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/* -------------------------------------------------------------------------- */
/*                              Auth User Profile                             */
/* -------------------------------------------------------------------------- */

Route::middleware('auth')->group(function () {

    Route::get('/profile', function () {
        return view('profile.index', [
            'user' => auth()->user(),
            'posts' => Post::where('removed', false)->latest()->whereHas('author', fn ($q) => $q->where('user_id', auth()->user()->id))->paginate(20),
            'notes' => Note::where('removed', false)->latest()->where('removed', '=', 0)->whereHas('author', fn ($q) => $q->where('user_id', auth()->user()->id))->get()->take(20),
            // 'notes' => auth()->user()->notes()->latest()->get()->take(20),
            // 'notes' => ['note1', 'note2'],
            'followers' => auth()->user()->followers,
            'following' => auth()->user()->following,
        ]);
    })->name('profile');

    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/settings/remove', [ProfileController::class, 'removeImage']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/wallpaper', [ProfileController::class, 'setCurrentWallpaper'])->name('set_current_wallpaper');
    Route::post('/profile/profile-picture-frame', [ProfileController::class, 'setCurrentProfilePictureFrame'])->name('set_current_profile_picture_frame');
    Route::post('/profile/colour', [ProfileController::class, 'setCurrentColour'])->name('set_current_colour');
});

require __DIR__ . '/auth.php';

/* -------------------------------------------------------------------------- */
/*                             Other User Profile                             */
/* -------------------------------------------------------------------------- */

Route::middleware('auth')->group(function () {
    Route::post("/follow", [FollowController::class, 'follow'])->name('user.follow');
    Route::post("/unfollow", [FollowController::class, 'unfollow'])->name('user.unfollow');
});

Route::get('/profile/{author:username}', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/{author:username}/posts', [ProfileController::class, 'posts'])->name('profile.posts');
Route::get('/profile/{author:username}/notes', [ProfileController::class, 'notes'])->name('profile.notes');
Route::get('/profile/{author:username}/shop', [ProfileController::class, 'shop'])->name('profile.shop');
Route::get('/profile/{author:username}/faq', [ProfileController::class, 'faq'])->name('profile.faq');
Route::get('/profile/{author:username}/about', [ProfileController::class, 'about'])->name('profile.about');

Route::get('/u/{author:username}/posts/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('/u/{author:username}/notes/{note:slug}#current', [NoteController::class, 'show'])->name('note.show');
Route::get('/u/{author:username}/notes/{note:slug}', [NoteController::class, 'show']);

Route::get('/', function () {
    if (!auth()->check())
        return redirect()->route('explore')->with('success', 'Log in to access your feed!');

    $userIds = auth()->user()->following()->pluck('follows.following_id');
    $userIds[] = auth()->user()->id;

    $posts = Post::whereIn('user_id', $userIds)->get();
    $notes = Note::whereIn('user_id', $userIds)->get();

    $items = $notes->merge($posts)->sortByDesc('created_at');

    return view('feed', [
        'items' => $items
    ]);
})->name('feed');


Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])->name('post.comment.store');
Route::delete('/posts/{post}/comment/{comment}/delete', [PostCommentController::class, 'store'])->name('post.comment.delete');
Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
Route::post('/notes/{note}/comments', [NoteController::class, 'store'])->name('note.comment.store');
Route::post('/notes/store', [NoteController::class, 'store'])->name('note.store');

Route::post('/post/delete', [PostController::class, 'destroy'])->name('post.delete');
Route::post('/note/delete', [NoteController::class, 'destroy'])->name('note.delete');

Route::get('/tags/{tag:slug}', [TagController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('post.like');
    Route::post('/comments/{postcomment}/like', [PostCommentController::class, 'toggleLike'])->name('postcomment.like');
    Route::post('/notes/{note}/like', [NoteController::class, 'toggleLike'])->name('note.like');
});


Route::post('/u/{user:id}/faq', [FAQuestionController::class, 'store']);



Route::get('/explore', [PostController::class, 'explore'])->name('explore');

/* -------------------------------------------------------------------------- */
/*                                  StarShop                                  */
/* -------------------------------------------------------------------------- */

Route::middleware('creator')->group(function () {
    Route::get('/starshop/wallpapers/create', [WallpaperController::class, 'create'])->name('starshop.wallpapers.create');
    Route::get('/starshop/profile-picture-frames/create', [ProfilePictureFrameController::class, 'create'])->name('starshop.profile-picture-frames.create');
    Route::get('/starshop/post-frames/create', [PostFrameController::class, 'create'])->name('starshop.post-frames.create');
});

Route::get('/starshop', [StarshopController::class, 'redirect'])->name('starshop')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/starshop', [StarshopController::class, 'index'])->name('starshop');

    Route::get('/starshop/wallpapers/{wallpaper}', [WallpaperController::class, 'show'])->name('starshop.wallpapers.show');
    Route::get('/starshop/wallpapers', [WallpaperController::class, 'index'])->name('starshop.wallpapers');
    Route::post('/starshop/wallpapers/store', [WallpaperController::class, 'store'])->name('starshop.wallpapers.store');
    Route::post('/starshop/wallpapers/delete', [WallpaperController::class, 'destroy'])->name('wallpaper.delete');

    Route::get('/starshop/profile-picture-frames/{profile_picture_frame}', [ProfilePictureFrameController::class, 'show'])->name('starshop.profile-picture-frames.show');
    Route::get('/starshop/profile-picture-frames', [ProfilePictureFrameController::class, 'index'])->name('starshop.profile-picture-frames');
    Route::post('/starshop/profile-picture-frames/store', [ProfilePictureFrameController::class, 'store'])->name('starshop.profile-picture-frames.store');
    Route::post('/starshop/profile-picture-frames/delete', [ProfilePictureFrameController::class, 'destroy'])->name('profile-picture-frame.delete');

    Route::get('/starshop/colours', [ColourController::class, 'index'])->name('starshop.colours');

    // Route::get('/starshop/post-frames/{post_frame}', [PostFrameController::class, 'show'])->name('starshop.post-frames.show');
    // Route::get('/starshop/post-frames', [PostFrameController::class, 'index'])->name('starshop.post-frames');
    // Route::post('/starshop/post-frames/store', [PostFrameController::class, 'store'])->name('starshop.post-frames.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/starshop/wallpapers/{wallpaper}/like', [WallpaperController::class, 'toggleLike'])->name('wallpaper.like');
    Route::post('/starshop/profile-picture-frames/{profile_picture_frame}/like', [ProfilePictureFrameController::class, 'toggleLike'])->name('profile-picture-frame.like');
    Route::post('/starshop/post-frames/{post_frame}/like', [PostFrameController::class, 'toggleLike'])->name('post-frame.like');
});

Route::middleware('auth')->group(function () {
    Route::post('/starshop/wallpapers/buy', [WallpaperController::class, 'buy'])->name('starshop.wallpapers.buy');
    Route::post('/starshop/profile-picture-frames/buy', [ProfilePictureFrameController::class, 'buy'])->name('starshop.profile-picture-frames.buy');
    Route::post('/starshop/colours/buy', [ColourController::class, 'buy'])->name('starshop.colours.buy');
    // Route::post('/starshop/post-frames/{post_frame}/buy', [StarshopController::class, 'postFrameToggleLike'])->name('post-frame.like');
});

Route::get('/help', function () {
    return view('help');
})->name('help');

/* -------------------------------------------------------------------------- */
/*                                Admin And Mod                               */
/* -------------------------------------------------------------------------- */

Route::get('/mod/dashboard', function () {
    return view('mod-dashboard', [
        'reported_users' => Report::where('reported_type', 'user')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get(),
        'reported_posts' => Report::where('reported_type', 'post')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get(),
        'reported_post_comments' => Report::where('reported_type', 'post-comment')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get(),
        'reported_notes' => Report::where('reported_type', 'note')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get(),
        'reported_wallpapers' => Report::where('reported_type', 'wallpaper')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get(),
        'reported_profile_picture_frames' => Report::where('reported_type', 'profile-picture-frame')->orderBy('resolved', 'ASC')->orderBy('created_at', 'ASC')->get()
    ]);
})->name('mod.dashboard')->middleware('mod');

Route::get('/mod/dashboard/{report}', function (Report $report) {
    return view('report-show', [
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
    // TODO remove all reports if a person gets banned
})->name('ban');


Route::post('/block/{user}', function (User $user) {
    // TODO block
})->name('block');

/* -------------------------------------------------------------------------- */
/*                                   Report                                   */
/* -------------------------------------------------------------------------- */


Route::middleware('auth')->group(function () {
    Route::post('/report/post/{post:slug}', [ReportController::class, 'store_post'])->name('report.post');
    Route::post('/report/comment/{comment:slug}', [ReportController::class, 'store_comment'])->name('report.comment');
    Route::post('/report/note/{note:slug}', [ReportController::class, 'store_note'])->name('report.note');
    Route::post('/report/starshop/{any?}', [ReportController::class, 'store_starshop'])->name('report.starshop.product');
    // Route::post('/report/wallpaper/{wallpaper}', [ReportController::class, 'store_wallpaper'])->name('report.wallpaper');
    // Route::post('/report/ppf/{profile_picture_frame}', [ReportController::class, 'store_profile_picture_frame'])->name('report.profile_picture_frame');
    Route::post('/report/user/{user:username}', [ReportController::class, 'store_user'])->name('report.user');
});

Route::post('/report/delete', [ReportController::class, 'destroy'])->name('report.delete')->middleware('mod');
Route::post('/report/approve', [ReportController::class, 'approve'])->name('report.approve')->middleware('mod');
