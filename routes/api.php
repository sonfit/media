<?php

use App\Http\Controllers\Api\V0Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-users', [ApiController::class, 'getUsers'])->name('api.getUsers');
Route::get('/get-domains', [ApiController::class, 'getDomains'])->name('api.getDomains');
Route::get('/get-tags', [ApiController::class, 'getTags'])->name('api.getTags');
Route::post('/get-domains-by-user', [ApiController::class, 'getDomainsbyUser'])->name('api.getDomainsByUser');


Route::group([], function() {
    Route::get('/categories', [V0Controller::class, 'categories']);

    Route::get('/categories/{category_id}/wallpapers', [V0Controller::class, 'wallpapersByCategories']);
    Route::get('/wallpaper-detail/{id}/{device_id?}', [V0Controller::class, 'wallpaper']);

    Route::get('/wallpapers/featured', [V0Controller::class, 'getFeatured']);
    Route::get('/wallpapers/popular', [V0Controller::class, 'getPopularity']);
    Route::get('/wallpapers/newest', [V0Controller::class, 'getNewest']);

    Route::post('/wallpaper-favorite', [V0Controller::class, 'likeWallpaper']);
    Route::post('/wallpaper-favorite-unsaved', [V0Controller::class, 'disLikeWallpaper']);
    Route::get('/favorite/{device_id?}', [V0Controller::class, 'getSaved']);

    //Ringtones

    Route::get('/categories/popular', [CategoriesController::class, 'getPopulared']);

    Route::get('categories/{category_id}/ringtones/{deviceId}', [RingtonesController::class, 'getRingtonesByCate']);
//
    Route::get('ringtone-detail/{id}/{device_id}', [RingtonesController::class, 'show']);
    Route::get('ringtones/featured', [RingtonesController::class, 'getFeatured']);
    Route::get('ringtones/popular/{deviceId}', [RingtonesController::class, 'getPopulared']);
    Route::get('ringtones/newest/{deviceId}', [RingtonesController::class, 'getNewest']);
////    Route::get('ringtones/premium', [RingtonesController::class, 'getPremium']);
    Route::get('ringtones/most-download/{deviceId}', [RingtonesController::class, 'getMostDownload']);
//
//
    Route::post('ringtone-favorite/', [FavoriteController::class, 'likeRingtone']);
    Route::post('ringtone-favorite-unsaved/', [FavoriteController::class, 'dislikeRingtone']);


//    Route::post('search', [SearchController::class, 'search']);


});

