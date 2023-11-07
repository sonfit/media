<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BasicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\IpblockController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ManageRolePermissionController;
use App\Http\Controllers\Admin\MusicsController;

use App\Http\Controllers\Admin\RingtonesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\WallpapersController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware(['checkAppUrl'])->group(function () {


    Route::get('/clear', function () {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        Artisan::call('optimize:clear', array(), $output);
        return $output->fetch();
    })->name('/clear');

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/', [LoginController::class, 'login'])->name('login');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
            Route::get('reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
            Route::post('email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
            Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('reset');
            Route::post('reset', [ResetPasswordController::class, 'reset'])->name('update');
        });
        Route::get('/403', [DashboardController::class, 'forbidden'])->name('403');
        Route::group(
            [
                'middleware' => [
                    'auth:admin',
//                    'permission'
                ]
            ], function () {

            Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
            Route::put('/profile', [DashboardController::class, 'profileUpdate'])->name('profileUpdate');
            Route::get('/password', [DashboardController::class, 'password'])->name('password');
            Route::put('/password', [DashboardController::class, 'passwordUpdate'])->name('passwordUpdate');

            Route::group(['prefix' => 'dashboard', 'as' => 'dashboard'], function () {
                Route::get('/', [DashboardController::class, 'dashboard']);
                Route::get('/getPlatformData', [DashboardController::class, 'getPlatformData'])->name('.getPlatformData');
                Route::get('/getCountryData', [DashboardController::class, 'getCountryData'])->name('.getCountryData');
                Route::get('/getYearlyData', [DashboardController::class, 'getYearlyData'])->name('.getYearlyData');
            });

            Route::group(['prefix' => 'staff','as' => 'staff'], function () {
                Route::get('/', [ManageRolePermissionController::class, 'index']);
                Route::post('/getIndex', [ManageRolePermissionController::class, 'getIndex'])->name('.getIndex');
                Route::post('/', [ManageRolePermissionController::class, 'store'])->name('.store');
                Route::get('edit', [ManageRolePermissionController::class, 'edit'])->name('.edit');
                Route::post('update', [ManageRolePermissionController::class, 'update'])->name('.update');
                Route::post('delete', [ManageRolePermissionController::class, 'delete'])->name('.delete');
            });

            Route::group(['prefix' => 'ipblocklist', 'as' => 'ipblock'], function () {
                Route::get('/', [IpblockController::class, 'index']);
                Route::post('/getIndex', [IpblockController::class, 'getIndex'])->name('.getIndex');
                Route::post('/', [IpblockController::class, 'store'])->name('.store');
                Route::get('edit/{id}', [IpblockController::class, 'edit'])->name('.edit');
                Route::post('update', [IpblockController::class, 'update'])->name('.update');
                Route::post('delete', [IpblockController::class, 'delete'])->name('.delete');
                Route::post('bulk-block-ips', [IpblockController::class, 'bulk_store'])->name('.bulk_store');
                Route::post('change-list-type', [IpblockController::class, 'changeListType'])->name('.changeListType');

            });

            Route::group(['prefix' => 'tags', 'as' => 'tags'], function () {
                Route::get('/', [TagsController::class, 'index']);
                Route::post('/getIndex', [TagsController::class, 'getIndex'])->name('.getIndex');
                Route::post('/', [TagsController::class, 'store'])->name('.store');
                Route::get('edit', [TagsController::class, 'edit'])->name('.edit');
                Route::post('update', [TagsController::class, 'update'])->name('.update');
                Route::post('delete', [TagsController::class, 'delete'])->name('.delete');
            });

            Route::group(['prefix' => 'domains', 'as' => 'domain'], function () {
                Route::get('/', [DomainController::class, 'index']);
                Route::post('/getIndex', [DomainController::class, 'getIndex'])->name('.getIndex');
                Route::post('/', [DomainController::class, 'store'])->name('.store');
                Route::get('edit/{id}', [DomainController::class, 'edit'])->name('.edit');
                Route::post('update', [DomainController::class, 'update'])->name('.update');
                Route::post('delete', [DomainController::class, 'delete'])->name('.delete');


                //Ads
                Route::get('{id}/manage-ads', [DomainController::class, 'manageAds'])->name('.manage_ads');
                Route::post('update-ads', [DomainController::class, 'updateAds'])->name('.updateAds');

                //Home
                Route::get('{id}/manage-home', [DomainController::class, 'manageHome'])->name('.manage_home');
                Route::post('update-home', [DomainController::class, 'updateHome'])->name('.updateHome');

                //Config
                Route::get('{id}/config', [DomainController::class, 'config'])->name('.config');
                Route::post('update-config', [DomainController::class, 'updateConfig'])->name('.updateConfig');

                //Category
                Route::get('{id}/categories', [DomainController::class, 'categories'])->name('.categories');
                Route::post('get-categories', [DomainController::class, 'getDomainCategories'])->name('.getDomainCategories');
                Route::post('category', [DomainController::class, 'storeCategory'])->name('.storeCategory');
                Route::get('edit-category', [DomainController::class, 'editCategory'])->name('.editCategory');
                Route::post('update-category', [DomainController::class, 'updateCategory'])->name('.updateCategory');
                Route::get('delete-category', [DomainController::class, 'deleteCategory'])->name('.deleteCategory');


                //Wallpaper
                Route::get('{id}/wallpapers', [DomainController::class, 'wallpapers'])->name('.wallpapers');
                Route::get('{id}/get-wallpapers', [DomainController::class, 'getDomainWallpapers'])->name('.getDomainWallpapers');

                //Ringtones
                Route::get('{id}/ringtones', [DomainController::class, 'ringtones'])->name('.ringtones');
                Route::post('{id}/get-ringtones', [DomainController::class, 'getDomainRingtones'])->name('.getDomainRingtones');

                //Musics
                Route::get('{id}/musics', [DomainController::class, 'musics'])->name('.musics');
                Route::post('{id}/get-musics', [DomainController::class, 'getDomainMusics'])->name('.getDomainMusics');

            });

            Route::group(['prefix' => 'wallpapers', 'as' => 'wallpapers'], function () {
                Route::get('/', [WallpapersController::class, 'index']);
                Route::get('/getIndex', [WallpapersController::class, 'getIndex'])->name('.getIndex');

                Route::post('create', [WallpapersController::class, 'store'])->name('.store');

                Route::get('edit/{id}', [WallpapersController::class, 'edit'])->name('.edit');
                Route::post('update', [WallpapersController::class, 'update'])->name('.update');
                Route::post('delete', [WallpapersController::class, 'delete'])->name('.delete');
                Route::post('delete-tag', [WallpapersController::class, 'deleteTag'])->name('.deleteTag');
                Route::get('compare-images', [WallpapersController::class, 'compareImages'])->name('.compareImages');
            });

            Route::group(['prefix' => 'ringtones', 'as' => 'ringtones'], function () {
                Route::get('/', [RingtonesController::class, 'index']);
                Route::post('/getIndex', [RingtonesController::class, 'getIndex'])->name('.getIndex');
                Route::post('create', [RingtonesController::class, 'store'])->name('.store');
                Route::get('edit/{id}', [RingtonesController::class, 'edit'])->name('.edit');
                Route::post('update', [RingtonesController::class, 'update'])->name('.update');
                Route::post('delete', [RingtonesController::class, 'delete'])->name('.delete');
                Route::post('delete-tag', [RingtonesController::class, 'deleteTag'])->name('.deleteTag');
                Route::get('compare-ringtones', [RingtonesController::class, 'compareRingtones'])->name('.compareRingtones');
                Route::get('md5-hash', [RingtonesController::class, 'md5Hash'])->name('.md5Hash');
            });

            Route::group(['prefix' => 'musics', 'as' => 'musics'], function () {
                Route::get('/', [MusicsController::class, 'index']);
                Route::post('/getIndex', [MusicsController::class, 'getIndex'])->name('.getIndex');
                Route::post('create', [MusicsController::class, 'store'])->name('.store');
                Route::get('edit/{id}', [MusicsController::class, 'edit'])->name('.edit');
                Route::post('update', [MusicsController::class, 'update'])->name('.update');
                Route::post('delete', [MusicsController::class, 'delete'])->name('.delete');
                Route::post('delete-tag', [MusicsController::class, 'deleteTag'])->name('.deleteTag');
                Route::get('get-info', [MusicsController::class, 'getInfo'])->name('.getInfo');
                Route::get('update-musics', [MusicsController::class, 'updateMusics'])->name('.updateMusics');
            });

            /* ======== CONTROLS ========== */
            Route::group(['prefix' => 'basic-controls', 'as' => 'basic-controls'], function () {
                Route::get('/', [BasicController::class, 'index']);
                Route::post('/', [BasicController::class, 'updateConfigure'])->name('.update');

                Route::post('/clear-user-logs', [BasicController::class, 'clearUserLogs'])->name('.clearUserLogs');
                Route::post('/clear-redirect-logs', [BasicController::class, 'clearRedirectLogs'])->name('.clearRedirectLogs');
            });

            Route::group(['prefix' => 'color-settings', 'as' => 'color-settings'], function () {
                Route::get('/', [BasicController::class, 'colorSettings']);
                Route::post('/', [BasicController::class, 'colorSettingsUpdate'])->name('.update');
            });

            /*====== Plugin =====*/
            Route::get('/plugin-config', [BasicController::class, 'pluginConfig'])->name('plugin.config');
            Route::match(['get', 'post'], 'tawk-config', [BasicController::class, 'tawkConfig'])->name('tawk.control');
            Route::match(['get', 'post'], 'fb-messenger-config', [BasicController::class, 'fbMessengerConfig'])->name('fb.messenger.control');
            Route::match(['get', 'post'], 'google-recaptcha', [BasicController::class, 'googleRecaptchaConfig'])->name('google.recaptcha.control');
            Route::match(['get', 'post'], 'google-analytics', [BasicController::class, 'googleAnalyticsConfig'])->name('google.analytics.control');


            /* ===== ADMIN Language SETTINGS ===== */
            Route::group(['prefix' => 'language', 'as' => 'language'], function () {
                Route::get('/', [LanguageController::class, 'index'])->name('.index');
                Route::get('create', [LanguageController::class, 'create'])->name('.create');
                Route::post('create', [LanguageController::class, 'store'])->name('.store');
                Route::get('{language}', [LanguageController::class, 'edit'])->name('.edit');
                Route::put('update/{language}', [LanguageController::class, 'update'])->name('.update');
                Route::delete('{language}', [LanguageController::class, 'delete'])->name('.delete');
                Route::get('keyword/{id}', [LanguageController::class, 'keywordEdit'])->name('.keywordEdit');
                Route::put('keyword/{id}', [LanguageController::class, 'keywordUpdate'])->name('.keywordUpdate');
                Route::post('importJson', [LanguageController::class, 'importJson'])->name('.importJson');

                Route::post('store-key/{id}', [LanguageController::class, 'storeKey'])->name('.storeKey');
                Route::put('update-key/{id}', [LanguageController::class, 'updateKey'])->name('.updateKey');
                Route::delete('delete-key/{id}', [LanguageController::class, 'eleteKey'])->name('.deleteKey');
            });

            /* ======== THEME SETTINGS ========== */
            Route::get('/logo-seo', [BasicController::class, 'logoSeo'])->name('logo-seo');
            Route::put('/logoUpdate', [BasicController::class, 'logoUpdate'])->name('logoUpdate');
            Route::put('/seoUpdate', [BasicController::class, 'seoUpdate'])->name('seoUpdate');
            Route::get('/breadcrumb', [BasicController::class, 'breadcrumb'])->name('breadcrumb');
            Route::put('/breadcrumb', [BasicController::class, 'breadcrumbUpdate'])->name('breadcrumbUpdate');
        });
    });
});





