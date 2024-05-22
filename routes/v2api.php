<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v2\Auth\AuthenticationController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::any('login', 'login');
    Route::get('user-demo-login/{role_id}', 'DemoUser');
    Route::post('forget-password', 'emailVerify');
    Route::get('student-profile-details/{id}', 'studentProfile');
    Route::get('general-settings', 'generalSettings');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', 'AuthenticationController@logout');
    Route::get('admin-teacher-homework', 'Homework\HomeworkController@adminTeacherhomework');
    Route::get('student-homework/{id}', 'Homework\HomeworkController@studentHomework');
    Route::get('parent-homework/{record_id}', 'Homework\HomeworkController@parentHomework');
    Route::get('student-homework-view/{class_id}/{section_id}/{homework}', 'Homework\HomeworkController@studentHomeworkView');
    Route::get('student-homework-file-download/{id}', 'Homework\HomeworkController@studentHomeworkFileDownload');
    
});
 

 