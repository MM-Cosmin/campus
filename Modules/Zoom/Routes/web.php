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

// Route::prefix('zoom')->group(function() {
//     Route::get('/', 'ZoomController@index');
// });

Route::group(['middleware' => ['subscriptionAccessUrl', 'subdomain']], function () {
    Route::prefix('zoom')->group(function () {
        Route::name('zoom.')->group(function () {
            Route::get('about', 'MeetingController@about');

            Route::get('meetings', 'MeetingController@index')->name('meetings')->middleware('userRolePermission:zoom.meetings');
            Route::get('meetings/parent', 'MeetingController@index')->name('parent.meetings')->middleware('userRolePermission:zoom.parent.meetings');
            Route::post('meetings', 'MeetingController@store')->name('meetings.store')->middleware('userRolePermission:zoom.virtual-class.store');
            Route::get('meetings-show/{id}', 'MeetingController@show')->name('meetings.show');
            Route::get('meetings-edit/{id}', 'MeetingController@edit')->name('meetings.edit')->middleware('userRolePermission:zoom.meetings.edit');
            Route::post('meetings/{id}', 'MeetingController@update')->name('meetings.update')->middleware('userRolePermission:zoom.meetings.edit');
            Route::delete('meetings/{id}', 'MeetingController@destroy')->name('meetings.destroy')->middleware('userRolePermission:zoom.meetings.destroy');
            
            Route::get('virtual-class', 'VirtualClassController@index')->name('virtual-class')->middleware('userRolePermission:zoom.virtual-class');
            Route::get('virtual-class/child/{id}', 'VirtualClassController@mychild')->name('parent.virtual-class')->middleware('userRolePermission:zoom.parent.virtual-class');
            Route::post('virtual-class', 'VirtualClassController@store')->name('virtual-class.store')->middleware('userRolePermission:zoom.meetings.store');
            Route::get('virtual-class-show/{id}', 'VirtualClassController@show')->name('virtual-class.show');
            Route::get('virtual-class-edit/{id}', 'VirtualClassController@edit')->name('virtual-class.edit')->middleware('userRolePermission:zoom.virtual-class.edit');
            Route::post('virtual-class/{id}', 'VirtualClassController@update')->name('virtual-class.update')->middleware('userRolePermission:zoom.virtual-class.edit');
            Route::delete('virtual-class/{id}', 'VirtualClassController@destroy')->name('virtual-class.destroy')->middleware('userRolePermission:zoom.virtual-class.destroy');
            
            Route::get('meeting-room/{id}', 'VirtualClassController@meetingStart')->name('virtual-class.join');
            Route::get('virtual-class-room/{id}', 'MeetingController@meetingStart')->name('meeting.join');
            Route::get('user-list-user-type-wise', 'MeetingController@userWiseUserList')->name('user.list.user.type.wise');
            Route::get('settings', 'SettingController@settings')->name('settings')->middleware('userRolePermission:zoom.settings');
            Route::get('user/settings', 'SettingController@userSettings')->name('userSettings')->middleware('userRolePermission:zoom.settings');
           
            Route::post('upload_document','VirtualClassController@updateVedio')->name('upload_document');
            Route::get('virtual-upload-vedio-file/{id}','VirtualClassController@fileUpload')->name('virtual-upload-vedio-file');
            Route::get('meeting-upload-vedio-file/{id}','MeetingController@fileUpload')->name('meeting-upload-vedio-file');
            Route::post('settings', 'SettingController@updateSettings')->name('settings.update');
            Route::post('ind/settings', 'SettingController@updateIndSettings')->name('ind.settings.update');
            Route::get('virtual-class-reports', 'ReportController@report')->name('virtual.class.reports.show')->middleware('userRolePermission:zoom.virtual.class.reports.show');
            Route::get('meeting-reports', 'ReportController@meetingReport')->name('meeting.reports.show')->middleware('userRolePermission:zoom.meeting.reports.show');
        });
    });
});
