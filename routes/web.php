<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\RatingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/movies', [CatalogController::class, 'movies'])->name('movies');
Route::get('/series', [CatalogController::class, 'series'])->name('series');
Route::get('/anime', [CatalogController::class, 'anime'])->name('anime');
Route::get('/donghua', [CatalogController::class, 'donghua'])->name('donghua');

Route::get('/search', [SearchController::class, 'index'])->middleware('throttle:search')->name('search');

Route::get('/genres', [\App\Http\Controllers\GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{genre:slug}', [\App\Http\Controllers\GenreController::class, 'show'])->name('genres.show');
Route::get('/movies/{content:slug}', [ContentController::class, 'showMovie'])->name('movies.show');
Route::get('/series/{content:slug}', [ContentController::class, 'showSeries'])->name('series.show');
Route::get('/anime/{content:slug}', [ContentController::class, 'showAnime'])->name('anime.show');
Route::get('/donghua/{content:slug}', [ContentController::class, 'showDonghua'])->name('donghua.show');

Route::get('/watch/movies/{content:slug}', [\App\Http\Controllers\WatchController::class, 'watchMovie'])->name('watch.movie');
Route::get('/watch/series/{content:slug}/{episode}', [\App\Http\Controllers\WatchController::class, 'watchSeries'])->name('watch.series');
Route::get('/watch/anime/{content:slug}/{episode}', [\App\Http\Controllers\WatchController::class, 'watchAnime'])->name('watch.anime');
Route::get('/watch/donghua/{content:slug}/{episode}', [\App\Http\Controllers\WatchController::class, 'watchDonghua'])->name('watch.donghua');

Route::get('/watch/movies/{content:slug}/download/{source}', [\App\Http\Controllers\DownloadController::class, 'downloadMovie'])->name('watch.download.movie');
Route::get('/watch/series/{content:slug}/{episode}/download/{source}', [\App\Http\Controllers\DownloadController::class, 'downloadEpisode'])->name('watch.download.series');
Route::get('/watch/anime/{content:slug}/{episode}/download/{source}', [\App\Http\Controllers\DownloadController::class, 'downloadEpisode'])->name('watch.download.anime');
Route::get('/watch/donghua/{content:slug}/{episode}/download/{source}', [\App\Http\Controllers\DownloadController::class, 'downloadEpisode'])->name('watch.download.donghua');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Features
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{content}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{content}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{episode}', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{episode}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    Route::get('/history', [WatchHistoryController::class, 'index'])->name('history.index');
    Route::post('/watch-history', [WatchHistoryController::class, 'store'])->name('watch-history.store');
    Route::delete('/history/{history}', [WatchHistoryController::class, 'destroy'])->name('history.destroy');

    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::post('/content/{content}/rating', [RatingController::class, 'store'])->name('ratings.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::resource('genres', \App\Http\Controllers\Admin\GenreController::class)->except(['show']);
    Route::resource('content', \App\Http\Controllers\Admin\ContentController::class)->except(['show']);
    Route::resource('content.seasons', \App\Http\Controllers\Admin\SeasonController::class)->except(['show']);
    Route::resource('content.seasons.episodes', \App\Http\Controllers\Admin\EpisodeController::class)->except(['show']);
    Route::resource('video-sources', \App\Http\Controllers\Admin\VideoSourceController::class)->except(['show']);
});

require __DIR__.'/auth.php';
