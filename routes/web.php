<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', function () {
    return view('posts', [
        'posts' => Post::latest()->get()
    ]);
});

Route::get('/posts/{post:slug}', [PostController::class, 'show']);

Route::get(
    '/notes/{note:slug}',
    function (Note $note) {
        return view('note', [
            'note' => $note
        ]);
    }
);

Route::get('/tags/{tag:slug}', function (Tag $tag) {
    return view('posts', [
        'posts' => $tag->posts
    ]);
});

Route::get('/u/{author:username}', function (User $author) {
    return view('profile', [
        'user' => $author
    ]);
});


Route::get('/explore', [PostController::class, 'index']);
Route::get('/starshop', function () {
    return view('starshop');
});
Route::get('/profile', function () {
    return view('profile');
});
Route::get('/help', function () {
    return view('help');
});


Route::get('register', [RegisterController::class, 'create']);
Route::post('register', [RegisterController::class, 'store']);
