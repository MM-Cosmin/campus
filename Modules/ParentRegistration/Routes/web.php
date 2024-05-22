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


Route::prefix('parentregistration')->middleware('subdomain')->group(function () {
    if (moduleStatusCheck('Saas')) {
        Route::group(['domain' => '{subdomain}.' . config('app.short_url')], function ($routes) {
            require ('tenant.php');
        });

        Route::group(['domain' => '{subdomain}'], function ($routes) {
            require ('tenant.php');
        });
    }

    require ('tenant.php');

});


Route::prefix('online')->middleware('subdomain')->group(function () {
    if (moduleStatusCheck('Saas')) {
        Route::group(['domain' => '{subdomain}.' . config('app.short_url')], function ($routes) {
            Route::get('{page_link}', 'ParentRegistrationController@registration')->name('parentregistration/registration');
        });

        Route::group(['domain' => '{subdomain}'], function ($routes) {
            Route::get('{page_link}', 'ParentRegistrationController@registration')->name('parentregistration/registration');
        });
    }
    Route::get('{page_link}', 'ParentRegistrationController@registration')->name('parentregistration/registration');
    

});