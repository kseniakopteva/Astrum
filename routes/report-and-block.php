<?php

use App\Http\Controllers\BanController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/* -------------------------------------------------------------------------- */
/*                              Report and Block                              */
/* -------------------------------------------------------------------------- */

Route::post('/block/user', [BanController::class, 'block_store'])->name('block');
Route::post('/unblock/user', [BanController::class, 'block_destroy'])->name('unblock');

Route::middleware('auth')->group(function () {
    Route::post('/report/post/{post:slug}', [ReportController::class, 'store_post'])->name('report.post');
    Route::post('/report/comment/{comment:slug}', [ReportController::class, 'store_comment'])->name('report.comment');
    Route::post('/report/note/{note:slug}', [ReportController::class, 'store_note'])->name('report.note');
    Route::post('/report/starshop/{any?}', [ReportController::class, 'store_starshop'])->name('report.starshop.product');
    Route::post('/report/user/{user:username}', [ReportController::class, 'store_user'])->name('report.user');
});

Route::post('/report/delete', [ReportController::class, 'destroy'])->name('report.delete')->middleware('mod');
Route::post('/report/approve', [ReportController::class, 'approve'])->name('report.approve')->middleware('mod');
