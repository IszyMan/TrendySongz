<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CommentController as PublicCommentController;





Route::get('/sitemaps.xml', [SitemapController::class, 'index'])->name('sitemap.index');
// Keep the previous address working.
Route::redirect('/sitemap_index.xml', '/sitemaps.xml', 301);

Route::get('/sitemap-style.xsl', [SitemapController::class, 'stylesheet'])->name('sitemap.style');

Route::get('/{type}-sitemap.xml', [SitemapController::class, 'show'])
    ->where(
        'type',
        'music|video|naija-music|ghana-music|african-music|album|blog|dj-mix|artists|dj'
    )
    ->defaults('page', '1')
    ->name('sitemap.first');

Route::get('/{type}-sitemap{page}.xml', [SitemapController::class, 'show'])
    ->where(
        'type',
        'music|video|naija-music|ghana-music|african-music|album|blog|dj-mix|artists|dj'
    )
    ->where('page', '[2-9]|[1-9][0-9]+')
    ->name('sitemap.page');


Route::get('/', [PagesController::class, 'home'])->name('pages.home');
Route::get('/home', [PagesController::class, 'home']);
Route::get('/musics', [PagesController::class, 'music_all'])->name('pages.music_all');

Route::get('/musics/{country}', [PagesController::class, 'musics'])
    ->whereIn('country', ['naija', 'ghana', 'african'])->name('pages.musics');


Route::get('/songs-posted-by/{slug}', [PagesController::class, 'songs_posted_by'])->name('pages.songs_posted_by');
Route::get('/download-mp3/{year}/songs', [PagesController::class, 'songs_released_year'])->whereNumber('year')
    ->name('pages.songs_released_year');    

Route::get('/download-mp3/{id}/{slug?}', [PagesController::class, 'musicDetail'])->whereNumber('id')->name('pages.musics_detail');
Route::get('/download-latest-videos', [PagesController::class, 'videos_all'])->name('pages.videos_all');
Route::get('/download-video/{id}/{slug?}', [PagesController::class, 'videoDetail'])->whereNumber('id')->name('pages.videos_detail');
Route::get('/{year}/albums', [PagesController::class, 'albums_released_year'])->where('year', '[0-9]{4}')->name('pages.albums_released_year');
Route::get('/artist-albums', [PagesController::class, 'albums'])->name('pages.album');
Route::get('/artist-albums/{id}/{slug?}',[PagesController::class, 'albumDetail'])->whereNumber('id')->name('pages.album_details');
Route::get('/album-posted-by/{slug}', [PagesController::class, 'albums_posted_by'])->name('pages.albums_posted_by');
Route::get('/djmix', [PagesController::class, 'djmix'])->name('pages.djmix');
Route::get('/dj-mix-{slug}', [PagesController::class, 'djDetail'])->name('pages.dj_details');
Route::get('/djmix/{id}/{slug?}', [PagesController::class, 'DJMixDetails'])->whereNumber('id')->name('pages.djmix_detail');
Route::get('/artists', [PagesController::class, 'artists'])->name('pages.artists');
Route::get('/artists/{artistSlug}', [PagesController::class, 'artistDetail'])->name('pages.artists_details');
Route::get('/music-news', [PagesController::class, 'MusicNews'])->name('pages.celebrity_news');
Route::get('/music-news/{id}/{slug?}', [PagesController::class, 'MusicNewsDetails'])->whereNumber('id')->name('pages.celebritynews_details');
Route::get('/search', [PagesController::class, 'search'])->name('pages.search');




Route::get('/videos/posted-by/{slug}', [PagesController::class, 'videos_posted_by'])->name('pages.videos_posted_by');
Route::get('/{year}/videos',[PagesController::class, 'videos_released_year'])->whereNumber('year')->name('pages.videos_released_year');




Route::post('/comments',[PublicCommentController::class, 'store'])
    ->middleware('throttle:3,1,ts-comment-submit')
    ->name('comments.store');

Route::post('/comments/{comment}/like',[PublicCommentController::class, 'like'])
    ->whereNumber('comment')
    ->middleware('throttle:30,1,ts-comment-like')
    ->name('comments.like');




    
    
    

require __DIR__.'/admin.php';




