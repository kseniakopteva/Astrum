<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/profile', function () {
    return view('profile', [
        'user' => auth()->user()
    ]);
})->middleware(['auth'])->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/', function () {
    return view('feed', [
        'posts' => Post::latest()->get()
    ]);
})->name('feed');

Route::get('/posts/{post:slug}', [PostController::class, 'show']);

Route::post('/post/store', [PostController::class, 'store'])->name('post.store');

Route::get(
    '/notes/{note:slug}',
    function (Note $note) {
        return view('note', [
            'note' => $note
        ]);
    }
);

Route::get('/tags/{tag:slug}', [TagController::class, 'index']);

Route::get('/u/{author:username}', function (User $author) {
    return view('profile', [
        'user' => $author
    ]);
});


Route::get('/explore', [PostController::class, 'index'])->name('explore');
Route::get('/starshop', function () {
    return view('starshop');
})->name('starshop');
Route::get('/help', function () {
    return view('help');
})->name('help');
