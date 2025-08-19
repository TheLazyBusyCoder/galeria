<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('signup');
});

// Auth routes
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Photos
    Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index'); // list all user photos
    Route::get('/photos/upload', [PhotoController::class, 'create'])->name('photos.create'); // show upload form
    Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store'); // save upload

    // Feed (All users)
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index'); // show feed page
    Route::post('/photos/{photo}/like', [LikeController::class, 'store'])->name('photos.like'); // like a photo
    Route::delete('/photos/{photo}/like', [LikeController::class, 'destroy'])->name('photos.unlike'); // unlike photo
    Route::post('/photos/{photo}/comments', [CommentController::class, 'store'])->name('photos.comment'); // add comment

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});