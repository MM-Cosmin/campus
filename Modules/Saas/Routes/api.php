<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/saas', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['XSS','auth:api','json.response']], function () {

    //Superadmin dashboard
    //'institution-enable
    Route::get('superadmin-dashboard','SaasApiController@dashboardData');

    //Registration
    Route::get('saas-school-list','SaasApiController@schoolList');
    Route::get('saas-academic-year/{school_id}','SaasApiController@schoolAcademicYear');
    Route::get('saas-school_classes/{school_id}/{academic_year}','SaasApiController@schoolAcademicYearClass');
    //localhost/laravel/101.infixedu.v5/api/saas-school_classes/1/2021

    Route::get('institution-enable', 'SaasApiController@institutionEnable');
    Route::get('institution-approve', 'SaasApiController@institutionApprove');

    Route::get('saas-school_sections/{class_id}','SaasApiController@schoolSections');


    // Route::get('school/{school_id}/teacher-class-list', 'SaasApiController@SaaSteacherClassList');


    Route::get('saas-student-list', 'SaasApiController@saasStudentListsearch');
    Route::get('student-approve', 'SaasApiController@studentApprove');
    Route::get('student-delete', 'SaasApiController@studentDelete');
    // Notice api
    Route::get('saas-send-notice','SaasApiController@sendNotice');
    Route::get('saas-notice-list','SaasApiController@noticeList');
    Route::get('administrator-notice','SaasApiController@administratorNotice');

    //Ticket
    Route::get('ticket-view','SaasApiController@ticketView');
    Route::post('add-ticket-comment','SaasApiController@comment_store');
    Route::get('add-ticket-view','SaasApiController@addTicketView');
    Route::get('ticket-store','SaasApiController@ticket_store');
    Route::get('ticket-comment-list/{id}','SaasApiController@ticket_comment_list');

    Route::post('saas-update-logo', 'SaasApiController@updateLogo');
    Route::post('saas-update-favicon', 'SaasApiController@updateFavicon');


    Route::post('update-school-settings-data', 'SaasApiController@updateSchoolSettingsData');






  

    // Parent registration setting
    
});