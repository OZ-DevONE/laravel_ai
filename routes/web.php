<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PhotoDashboardController;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::resource('photos', PhotoController::class);
});

Route::get('/photo', [PhotoDashboardController::class, 'index'])->name('photo.index');
Route::middleware('auth')->group(function () {
    Route::post('/photo/{photo}/comment', [PhotoDashboardController::class, 'storeComment'])->name('photo.comment.store');
    Route::patch('/comment/{comment}', [PhotoDashboardController::class, 'updateComment'])->name('comment.update');
    Route::delete('/comment/{comment}', [PhotoDashboardController::class, 'destroyComment'])->name('comment.destroy');
    Route::post('/photo/{photo}/like', [PhotoDashboardController::class, 'likePhoto'])->name('photo.like');
    Route::post('/photo/{photo}/dislike', [PhotoDashboardController::class, 'dislikePhoto'])->name('photo.dislike');
});