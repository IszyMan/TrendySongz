<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'home'])->name('pages.home');
Route::get('/home', [PagesController::class, 'home']);
Route::get('/musics', [PagesController::class, 'music_all'])->name('pages.music_all');

Route::get('/musics/{country}', [PagesController::class, 'musics'])
    ->whereIn('country', ['naija', 'ghana', 'african'])->name('pages.musics');

Route::get('/download-mp3/{id}/{slug?}', [PagesController::class, 'musicDetail'])->whereNumber('id')->name('pages.musics_detail');
Route::get('/download-latest-videos', [PagesController::class, 'videos_all'])->name('pages.videos_all');
Route::get('/download-video/{id}/{slug?}', [PagesController::class, 'videoDetail'])->whereNumber('id')->name('pages.videos_detail');
Route::get('/albums', [PagesController::class, 'albums'])->name('pages.album');
Route::get('/albums/{id}/{slug?}', [PagesController::class, 'albumDetail'])->whereNumber('id')->name('pages.album_details');
Route::get('/dj-mixes', [PagesController::class, 'mixes'])->name('pages.djmix');
Route::get('/dj/{slug}', [PagesController::class, 'djDetail'])->name('pages.dj_details');
Route::get('/dj-mix/{id}/{slug?}', [PagesController::class, 'mixDetail'])->whereNumber('id')->name('pages.djmix_detail');
Route::get('/artists', [PagesController::class, 'artists'])->name('pages.artists');
Route::get('/artists/{id}/{slug?}', [PagesController::class, 'artistDetail'])->whereNumber('id')->name('pages.artists_details');
Route::get('/music-news', [PagesController::class, 'news'])->name('pages.celebritynews_hot_gist');
Route::get('/music-news/{id}/{slug?}', [PagesController::class, 'newsDetail'])->whereNumber('id')->name('pages.celebritynews_details');
Route::get('/search', [PagesController::class, 'search'])->name('pages.search');
