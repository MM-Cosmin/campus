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
    Route::prefix('aicontent')->middleware('auth')->group(function () {
        Route::get('settings', 'AiSettingController@index')->name('ai-content.settings');
        Route::post('settings/update', 'AiSettingController@store')->name('ai-content.settings-update');

        Route::get('content', 'AiContentController@index')->name('ai-content.content');
        Route::post('content/update', 'AiContentController@update')->name('ai-content.update');
        Route::get('delete/{id}', 'AiContentController@delete')->name('ai-content.delete');
        Route::post('generate-text', 'AiContentController@generate')->name('ai-content.generate_text');
    });
});
