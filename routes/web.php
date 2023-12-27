<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/user-profile.php';

Route::get('/', [PostController::class, 'feed'])->name('feed');

Route::get('/tags/{tag:slug}', [TagController::class, 'index']);

Route::get('/explore', [PostController::class, 'explore'])->name('explore');
Route::get('/explore/page/{page}', ['as' => 'explore-page', 'uses' => '\App\Http\Controllers\PostController@explore']);

/* -------------------------- Single post and note -------------------------- */
Route::get('/u/{author:username}/posts/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('/u/{author:username}/notes/{note:slug}#current', [NoteController::class, 'show'])->name('note.show');
Route::get('/u/{author:username}/notes/{note:slug}', [NoteController::class, 'show']);

/* --------------- Post, note, comment: creating and deleting --------------- */
Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
Route::post('/post/delete', [PostController::class, 'destroy'])->name('post.delete');
Route::post('/notes/store', [NoteController::class, 'store'])->name('note.store');
Route::post('/notes/{note}/comments', [NoteController::class, 'store'])->name('note.comment.store');
Route::post('/note/delete', [NoteController::class, 'destroy'])->name('note.delete');
Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])->name('post.comment.store');
Route::delete('/posts/{post}/comment/{comment}/delete', [PostCommentController::class, 'destroy'])->name('post.comment.delete');

/* ----------------------- Post, note, comment: likes ----------------------- */
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('post.like');
    Route::post('/comments/{postcomment}/like', [PostCommentController::class, 'toggleLike'])->name('postcomment.like');
    Route::post('/notes/{note}/like', [NoteController::class, 'toggleLike'])->name('note.like');
});

Route::get('/help', function () {
    return view('help');
})->name('help');

require __DIR__ . '/starshop.php';

require __DIR__ . '/mod-and-admin.php';

require __DIR__ . '/report-and-block.php';
