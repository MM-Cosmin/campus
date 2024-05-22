<?php

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
    Route::prefix('bbb')->group(function () {
        Route::get('/', 'BBBController@index');
    });

    Route::prefix('bbb')->group(function () {
        Route::name('bbb.')->middleware('auth')->group(function () {
            Route::get('virtual-class', 'BbbVirtualClassController@index')->name('virtual-class');
            Route::get('virtual-class/child/{id}', 'BbbVirtualClassController@myChild')->name('parent.virtual-class')->middleware('userRolePermission:bbb.parent.virtual-class');

            Route::post('virtual-class/store', 'BbbVirtualClassController@store')->name('virtual-class.store');
            Route::delete('virtual_class/{id}', 'BbbVirtualClassController@destroy')->name('virtual_class.destroy');
            Route::get('virtual-class-show/{id}', 'BbbVirtualClassController@show')->name('virtual-class.show');
            Route::get('virtual-class-edit/{id}', 'BbbVirtualClassController@edit')->name('virtual-class.edit');
            Route::post('virtual-class/{id}', 'BbbVirtualClassController@update')->name('virtual-class.update');

            Route::get('meetings', 'BbbMeetingController@index')->name('meetings');
            Route::get('meetings/parent', 'BbbMeetingController@index')->name('parent.meetings')->middleware('userRolePermission:bbb.parent.meetings');

            Route::post('meetings', 'BbbMeetingController@store')->name('meetings.store');
            Route::get('meetings-show/{id}', 'BbbMeetingController@show')->name('meetings.show');
            Route::get('meetings-edit/{id}', 'BbbMeetingController@edit')->name('meetings.edit');
            Route::post('meetings/{id}', 'BbbMeetingController@update')->name('meetings.update');
            Route::delete('meetings/{id}', 'BbbMeetingController@destroy')->name('meetings.destroy');
            Route::get('settings', 'BbbSettingController@settings')->name('settings');
            Route::post('settings', 'BbbSettingController@updateSettings')->name('settings.update');
            Route::get('user-list-user-type-wise', 'BbbMeetingController@userWiseUserList')->name('user.list.user.type.wise');

            Route::post('meetings', 'BbbMeetingController@store')->name('meetings.store');
            Route::get('meeting-start/{id}', 'BbbMeetingController@meetingStart')->name('meeting.start');
            Route::get('meeting-join/{id}', 'BbbMeetingController@meetingJoin')->name('meeting.join');

            Route::get('class-recording-list', 'BbbReportController@recordingList')->name('class.recording.list');
            Route::get('meeting-recording-list', 'BbbReportController@recordingMeetingList')->name('meeting.recording.list');

            Route::get('class-recording-list/child/{id}', 'BbbReportController@myChild')->name('parent.class.recording.list')->middleware('userRolePermission:bbb.parent.class.recording.list');
            Route::get('meeting-recording-list/parent', 'BbbReportController@recordingMeetingList')->name('parent.meeting.recording.list')->middleware('userRolePermission:bbb.parent.meeting.recording.list');


            Route::get('meeting-start-attendee/{course_id}/{meeting_id}', 'BbbMeetingController@meetingStartAsAttendee')->name('meetingStartAsAttendee');
            //reprot
            Route::get('virtual-class-reports.', 'BbbReportController@index')->name('virtual.class.reports.show');
            Route::get('meeting-reports', 'BbbReportController@meetingReport')->name('meeting.reports.show');
        });
    });
});
