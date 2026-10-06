<?php

use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DjController;
use App\Http\Controllers\Admin\DjMixController;
use App\Http\Controllers\Admin\AlbumController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
});

    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('can:manage-content')->group(function () {
        Route::resource('artists', ArtistController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('listings', ListingController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('djs', DjController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('dj-mixes', DjMixController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('albums', AlbumController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::middleware('can:manage-blogs')->group(function () {
        Route::resource('blogs', BlogController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)
            ->only(['index', 'create', 'store', 'destroy']);
    });
    
    Route::middleware('can:manage-users')->group(function () {
    Route::get('/comments',[AdminCommentController::class, 'index'])->name('comments.index');

    Route::patch('/comments/{comment}',[AdminCommentController::class, 'update'])
        ->whereNumber('comment')
        ->name('comments.update');

    Route::delete('/comments/{comment}',[AdminCommentController::class, 'destroy'])
        ->whereNumber('comment')
        ->name('comments.destroy');
});
});
