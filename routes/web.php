<?php

use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\Admin\AdminReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PhotoDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FavoriteController;

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
        // Роутеры для работы с жалобами
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/{photoId}', [ReportController::class, 'store'])->name('reports.store');
        Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('/reports/{id}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
        
        // Подача жалобы
        Route::post('/photo/{photo}/report', [ReportController::class, 'store'])->name('report.store');

        // Оставить коментарий на фото
        Route::post('/photo/{photo}/comment', [PhotoDashboardController::class, 'storeComment'])->name('photo.comment.store');
        Route::patch('/comment/{comment}', [PhotoDashboardController::class, 'updateComment'])->name('comment.update');
        Route::delete('/comment/{comment}', [PhotoDashboardController::class, 'destroyComment'])->name('comment.destroy');
        Route::post('/photo/{photo}/like', [PhotoDashboardController::class, 'likePhoto'])->name('photo.like');
        Route::post('/photo/{photo}/dislike', [PhotoDashboardController::class, 'dislikePhoto'])->name('photo.dislike');

        // Избранные
        Route::get('/image/favorites', [FavoriteController::class, 'viewFavorites'])->name('photo.favorites');
        Route::post('/image/{photo}/favorite', [FavoriteController::class, 'addToFavorites'])->name('photo.favorite');
        Route::post('/image/{photo}/unfavorite', [FavoriteController::class, 'removeFromFavorites'])->name('photo.unfavorite');
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

        Route::resource('reports', AdminReportController::class);
        
        Route::post('comments/{comment}/destroy', [AdminPhotoController::class, 'destroyComment'])->name('comments.destroy');    
    });    
});
