<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ParentRegistrationController@index');
Route::get('/about', 'ParentRegistrationController@about');
Route::get('/registration', 'ParentRegistrationController@registrationOld');

Route::get('/get-class-academicyear', 'ParentRegistrationController@getClasAcademicyear');
Route::get('/get-section', 'ParentRegistrationController@getSection');

Route::get('/get-classes', 'ParentRegistrationController@getClasses');

Route::post('/student-store', 'ParentRegistrationController@studentStore')->name('parentregistration-student-store');

Route::get('/saas-student-list', 'ParentRegistrationController@saasStudentList')->name('parentregistration/saas-student-list');
Route::post('/saas-student-list', 'ParentRegistrationController@saasStudentListsearch')->name('parentregistration/saas-student-list');

Route::get('assign-section/{id}', 'ParentRegistrationController@assignSection');
Route::get('student-approve/{id}', 'ParentRegistrationController@editStudent')->name('parentregistration/student-approve');
Route::post('assign-section-store', 'ParentRegistrationController@assignSectionStore')->name('parentregistration/assign-section-store');

Route::get('/student-list', 'ParentRegistrationController@studentList')->name('parentregistration.student-list')->middleware('userRolePermission:parentregistration.student-list');
Route::post('/student-list', 'ParentRegistrationController@studentListSearch');

Route::post('student-approve', 'ParentRegistrationController@studentApprove')->name('parentregistration/student-approve')->middleware('userRolePermission:parentregistration/student-approve');
Route::get('student-view/{id}', 'ParentRegistrationController@studentView')->name('parentregistration/student-view')->middleware('userRolePermission:parentregistration/student-view');

Route::post('student-delete', 'ParentRegistrationController@studentDelete')->name('parentregistration/student-delete')->middleware('userRolePermission:parentregistration/student-delete');

Route::get('check-student-email', 'ParentRegistrationController@checkStudentEmail');

Route::get('check-student-mobile', 'ParentRegistrationController@checkStudentMobile');

Route::get('check-guardian-email', 'ParentRegistrationController@checkGuardianEmail');

Route::get('check-guardian-mobile', 'ParentRegistrationController@checkGuardianMobile');

// setting route
Route::get('settings', 'ParentRegistrationController@settings')->name('parentregistration/settings')->middleware('userRolePermission:parentregistration/settings');
Route::post('settings', 'ParentRegistrationController@Updatesettings')->name('parentregistration/settings');

Route::post('update-field-label','ParentRegistrationController@labelUpdate')->name('parentregistration/labelUpdate');
Route::post('field/switch','ParentRegistrationController@labelStatus')->name('parentregistration/labelStatus');