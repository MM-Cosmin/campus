<?php


use Illuminate\Support\Facades\Route;

Route::prefix('inappliveclass')->middleware('auth')->group(function () {
    Route::get('/', 'InAppLiveClassController@index')->name('inappliveclass.setting');
    Route::post('/', 'InAppLiveClassController@store');

    Route::delete('meetings/{id}', 'InAppLiveClassController@destroy')->name('inappliveclass.meetings.destroy');


    Route::get('meetings-show/{id}', 'InAppLiveClassController@joinToAgora')->name('inappliveclass.meetings.show');
    Route::get('meetings-end/{id}', 'InAppLiveClassController@endAgora')->name('inappliveclass.meetings.end');

});
