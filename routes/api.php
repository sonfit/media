<?php

use App\Http\Controllers\Api\V0Controller;
use App\Http\Controllers\Api\V2Controller;
use App\Http\Controllers\Api\V4Controller;
use App\Http\Controllers\Api\V7Controller;
use App\Http\Controllers\Api\V9Controller;
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
    Route::get('/categories/{category_id}/wallpapers', [V0Controller::class, 'wallpapersByCategory']);
    Route::get('/wallpaper-detail/{id}/{device_id?}', [V0Controller::class, 'wallpaper']);
    Route::get('/wallpapers/featured', [V0Controller::class, 'getFeatured']);
    Route::get('/wallpapers/popular', [V0Controller::class, 'getPopularity']);
    Route::get('/wallpapers/newest', [V0Controller::class, 'getNewest']);
    Route::post('/wallpaper-favorite', [V0Controller::class, 'likeWallpaper']);
    Route::post('/wallpaper-favorite-unsaved', [V0Controller::class, 'disLikeWallpaper']);
    Route::get('/favorite/{device_id?}', [V0Controller::class, 'getSaved']);

    //Ringtones

    Route::get('ringtones/featured', [V0Controller::class, 'getFeaturedRingtones']);
    Route::get('ringtones/newest/{deviceId?}', [V0Controller::class, 'getNewestRingtones']);
    Route::get('ringtones/popular/{deviceId?}', [V0Controller::class, 'getPopularityRingtones']);
    Route::get('ringtones/most-download/{deviceId?}', [V0Controller::class, 'getMostDownloadRingtones']);
    Route::get('/categories/popular', [V0Controller::class, 'categories']);
    Route::get('categories/{category_id}/ringtones/{deviceId?}', [V0Controller::class, 'ringtonesByCategory']);
    Route::get('ringtone-detail/{id}/{device_id?}', [V0Controller::class, 'ringtone']);
    Route::post('search', [V0Controller::class, 'searchRingtones']);
    Route::post('ringtone-favorite/', [V0Controller::class, 'likeRingtone']);
    Route::post('ringtone-favorite-unsaved/', [V0Controller::class, 'dislikeRingtone']);



});

Route::group([
    "prefix" => "v2"
], function() {
    Route::post('/getData',[V2Controller::class, 'getData']);
});

Route::group([
    "prefix" => "v4",
], function() {

    //===================== WALLPAPERS ===============================
    Route::get('admob',[V4Controller::class, 'admob']);
    Route::get('settings',[V4Controller::class, 'settings']);
    Route::get('home',[V4Controller::class, 'home']);
    Route::get('categories',[V4Controller::class, 'categories']);

    Route::get('wallpaper',[V4Controller::class, 'wallpaper']);
    Route::get('wallpaper/popular',[V4Controller::class, 'popular']);
    Route::get('wallpaper/download',[V4Controller::class, 'download']);
    Route::get('wallpaper/random',[V4Controller::class, 'random']);
    Route::get('wallpaper/live',[V4Controller::class, 'live']);
    Route::get('wallpaper/cid',[V4Controller::class, 'cid']);
    Route::get('wallpaper/hashtag',[V4Controller::class, 'hashtag']);
    Route::get('add/show/wallpaper',[V4Controller::class, 'viewWallpaper']);

    //===================== MUSICS ===============================
    Route::post('/app_details', [V4Controller::class, 'app_details']);
    Route::post('/home', [V4Controller::class, 'homeMusics']);
    Route::post('/home_collections', [V4Controller::class, 'home_collections']);
    Route::post('/trending_songs', [V4Controller::class, 'trending_songs']);
    Route::post('/song_by_category', [V4Controller::class, 'song_by_category']);
    Route::post('/home_slider_songs', [V4Controller::class, 'home_slider_songs']);
    Route::post('/home_recently_songs', [V4Controller::class, 'home_recently_songs']);
    Route::post('/category', [V4Controller::class, 'getCategoriesMusic']);
    Route::post('/all_musics', [V4Controller::class, 'all_musics']);
    Route::post('/latest_songs', [V4Controller::class, 'latest_songs']);
    Route::post('/song_view', [V4Controller::class, 'song_view']);
    Route::post('/song_download', [V4Controller::class, 'song_download']);
    Route::post('/song_favourite', [V4Controller::class, 'song_favourite']);
    Route::post('/user_favourite_songs', [V4Controller::class, 'user_favourite_songs']);
    Route::post('/search', [V4Controller::class, 'search']);
    Route::post('/search_single', [V4Controller::class, 'search_single']);
});

Route::group([
    "prefix" => "v7",
], function() {
    Route::get('getJson',[V7Controller::class, 'getJson']);
    Route::get('getJsonV8',[V7Controller::class, 'getJsonV8']);
    Route::get('status',[V7Controller::class, 'status'])->name('v7.status');
    Route::get('categories',[V7Controller::class, 'categories']);
    Route::get('action',[V7Controller::class, 'action']);
});
Route::get('wallpaper/{id}',[V7Controller::class, 'showWallpaper']);
Route::get('wallpaperThumb/{id}',[V7Controller::class, 'showWallpaperThumb']);



Route::group([
    "prefix" => "v9",
], function() {
    Route::get('get_app_details',[V9Controller::class, 'get_app_details']);
    Route::get('get_featured_img_cat',[V9Controller::class, 'get_featured_img_cat']);
    Route::get('get_img_cat',[V9Controller::class, 'get_img_cat']);

    Route::get('get_trending_gif_List',[V9Controller::class, 'get_trending_gif_List']);
    Route::get('get_home_gif_List',[V9Controller::class, 'get_home_gif_List']);

    Route::get('get_home_img_list',[V9Controller::class, 'get_home_img_list']);
    Route::get('get_home_img_list_recent',[V9Controller::class, 'get_home_img_list_recent']);
    Route::get('get_home_img_list_popular',[V9Controller::class, 'get_home_img_list_popular']);
    Route::get('get_img_list',[V9Controller::class, 'get_img_list']);

    Route::post('wallpaper_download_count',[V9Controller::class, 'wallpaper_download_count']);
    Route::post('wallpaper_view_count',[V9Controller::class, 'wallpaper_view_count']);

    Route::get('get_img_search',[V9Controller::class, 'get_img_search']);

    Route::get('get_ringtone_search',[V9Controller::class, 'get_ringtone_search']);
    Route::get('get_ringtone_List',[V9Controller::class, 'get_ringtone_List']);
    Route::get('get_home_ringtone_List',[V9Controller::class, 'get_home_ringtone_List']);
    Route::get('get_featured_ringtone_cat',[V9Controller::class, 'get_featured_ringtone_cat']);
    Route::get('get_ringtone_cat',[V9Controller::class, 'get_ringtone_cat']);
    Route::post('ringtone_download_count',[V9Controller::class, 'ringtone_download_count']);

});

