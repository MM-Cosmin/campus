<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('appslider')->group(function () {
        Route::name('appslider.')->middleware('auth')->group(function () {
            Route::get('/', 'AppSliderController@index')->name('index')->middleware('userRolePermission:appslider.index');
            Route::post('save-app-slider', 'AppSliderController@store')->name('store')->middleware('userRolePermission:appslider.store');
            Route::get('edit-app-slider/{id}', 'AppSliderController@edit')->name('edit')->middleware('userRolePermission:appslider.edit');
            Route::post('update-app-slider', 'AppSliderController@update')->name('update')->middleware('userRolePermission:appslider.edit');
            Route::get('delete-app-slider/{id}', 'AppSliderController@destroy')->name('delete')->middleware('userRolePermission:appslider.delete');
        });
    });

    Route::group(['middleware' => ['CheckDashboardMiddleware', 'XSS', 'subscriptionAccessUrl']], function () {
        Route::get('/saas/appslider', 'AppSliderController@index')->name('appslider.saas.index')->middleware('userRolePermission:930');
        Route::get('/saas/appslider/edit-app-slider/{id}', 'AppSliderController@edit')->name('appslider.saas.edit')->middleware('userRolePermission:appslider.edit');
    });
});
