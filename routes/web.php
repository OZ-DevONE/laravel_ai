<?php

use App\Http\Controllers\Admin\AdminPhotoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PhotoDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['banned'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware(['auth'])->group(function () {
        Route::resource('photos', PhotoController::class);
    });

    Route::get('/photo', [PhotoDashboardController::class, 'index'])->name('photo.index');

    Route::get('/photo/{id}', [PhotoDashboardController::class, 'show'])->name('photo.show');
    
    Route::middleware('auth')->group(function () {
        Route::post('/photo/{photo}/comment', [PhotoDashboardController::class, 'storeComment'])->name('photo.comment.store');
        Route::patch('/comment/{comment}', [PhotoDashboardController::class, 'updateComment'])->name('comment.update');
        Route::delete('/comment/{comment}', [PhotoDashboardController::class, 'destroyComment'])->name('comment.destroy');
        Route::post('/photo/{photo}/like', [PhotoDashboardController::class, 'likePhoto'])->name('photo.like');
        Route::post('/photo/{photo}/dislike', [PhotoDashboardController::class, 'dislikePhoto'])->name('photo.dislike');
    });

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
        
        // Роуты для AdminPhotoController
        Route::resource('adminphoto', AdminPhotoController::class);
    
        // Роуты для работы с комментариями
        Route::put('comments/{comment}', [AdminPhotoController::class, 'updateComment'])->name('comments.update');
        Route::delete('comments/{comment}', [AdminPhotoController::class, 'destroyComment'])->name('comments.destroy');
    });    
});
