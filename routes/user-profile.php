<?php

use App\Http\Controllers\FAQuestionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Note;
use App\Models\Post;
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
            'followers' => auth()->user()->followers,
            'following' => auth()->user()->following,
        ]);
    })->name('profile');

    /* ----------------------------- Editing profile ---------------------------- */
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/settings/remove', [ProfileController::class, 'removeImage']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* --------------------------- Customizing Profile -------------------------- */
    Route::post('/profile/wallpaper', [ProfileController::class, 'setCurrentWallpaper'])->name('set_current_wallpaper');
    Route::post('/profile/profile-picture-frame', [ProfileController::class, 'setCurrentProfilePictureFrame'])->name('set_current_profile_picture_frame');
    Route::post('/profile/colour', [ProfileController::class, 'setCurrentColour'])->name('set_current_colour');
});

require __DIR__ . '/auth.php';

/* -------------------------------------------------------------------------- */
/*                             Other User Profile                             */
/* -------------------------------------------------------------------------- */

/* -------------------------------- Following ------------------------------- */
Route::middleware('auth')->group(function () {
    Route::post("/follow", [FollowController::class, 'follow'])->name('user.follow');
    Route::post("/unfollow", [FollowController::class, 'unfollow'])->name('user.unfollow');
});

/* ---------------------------- Profile Sections ---------------------------- */
Route::get('/profile/{author:username}', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/{author:username}/posts', [ProfileController::class, 'posts'])->name('profile.posts');
Route::get('/profile/{author:username}/notes', [ProfileController::class, 'notes'])->name('profile.notes');
Route::get('/profile/{author:username}/shop', [ProfileController::class, 'shop'])->name('profile.shop');
Route::get('/profile/{author:username}/shop/page/{page}', [ProfileController::class, 'shop']);
Route::get('/profile/{author:username}/faq', [ProfileController::class, 'faq'])->name('profile.faq');
Route::get('/profile/{author:username}/about', [ProfileController::class, 'about'])->name('profile.about');

/* ---------------------------- Orders and status --------------------------- */
Route::get('/orders', [OrderController::class, 'index'])->name('orders')->middleware('auth');
Route::post('/order/status/update', [OrderController::class, 'update'])->name('order.status')->middleware('creator');
Route::post('/order/confirm', [OrderController::class, 'confirmComplete'])->name('order.confirm')->middleware('auth');
Route::post('/order/reject', [OrderController::class, 'rejectComplete'])->name('order.reject')->middleware('auth');

/* -------------------------- FAQ, About, Products -------------------------- */
Route::post('/profile/{author:username}/faq', [FAQuestionController::class, 'store']);
Route::post('/profile/faq', [FAQuestionController::class, 'destroy'])->name('faq.delete');

Route::post('/profile/about', [ProfileController::class, 'about_update'])->name('about.update');
Route::post('/profile/about/store/link', [ProfileController::class, 'about_store_link'])->name('about.link.store');
Route::post('/profile/about/destroy/link', [ProfileController::class, 'about_destroy_link'])->name('about.link.destroy');

Route::post('/profile/shop/store', [ProductController::class, 'store'])->name('product.store');
Route::post('/profile/shop/buy', [ProductController::class, 'buy'])->name('product.buy');
Route::post('/profile/product/delete', [ProductController::class, 'destroy'])->name('product.delete');
Route::post('/profile/product/active', [ProductController::class, 'makeActive'])->name('product.active');
