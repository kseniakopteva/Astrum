<?php

use App\Models\Post;
use App\Models\Tag;
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
    return view('posts', ['posts' => Post::all()]);
});

Route::get('posts/{post:slug}', function (Post $post) {
    return view('post', ['post' => $post]);
});

Route::get('tags/{tag:slug}', function (Tag $tag) {
    return view('posts', ['posts' => $tag->posts]);
});


Route::get('starshop', function () {
    return view('starshop');
});
Route::get('profile', function () {
    return view('profile');
});
Route::get('help', function () {
    return view('help');
});
