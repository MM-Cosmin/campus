<?php

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
Route::get('/appslider/without-auth', 'Api\AppSliderApiController@index');
Route::get('/appslider/saas', 'Api\AppSliderApiController@saasAdmin');


Route::middleware('auth:api')->group(function () {
    Route::get('/appslider', 'Api\AppSliderApiController@index');
    Route::get('/appslider/{school_id}', 'Api\AppSliderApiController@saasGetSliders');
    Route::post('/appslider/save-app-slider', 'Api\AppSliderApiController@store');
    Route::get('/appslider/edit-app-slider/{id}', 'Api\AppSliderApiController@edit');
    Route::post('/appslider/update-app-slider', 'Api\AppSliderApiController@update');
    Route::get('/appslider/delete-app-slider/{id}', 'Api\AppSliderApiController@destroy');
});



