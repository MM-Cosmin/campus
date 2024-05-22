<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['subdomain']], function () {
    Route::get('user/verify/{token}', 'RegistrationController@userVerify');
    Route::get('secret-login/{id}', 'InstituteController@secretLogin')->name('secret-login');
    Route::prefix('saas')->group(function () {
        Route::get('/', 'SaasController@saasDashboard');
        Route::get('about', 'SaasController@about');
    });

    Route::get('institution-register-new', 'RegistrationController@institution_register_new')->name('institution-register-new');
    Route::post('institution-register-store', 'RegistrationController@institutionNewStore')->name('administrator/institution-register-store');
    Route::post('/domain/validate', 'RegistrationController@validate')->name('saas.domain.validate');


// Route::group(['middleware' => ['CheckDashboardMiddleware']], function () {
    Route::group(['middleware' => ['CheckDashboardMiddleware', 'XSS', 'subscriptionAccessUrl']], function () {

        Route::get('/superadmin-dashboard', 'SaasController@index')->name('superadmin-dashboard')->middleware('userRolePermission:1');

        Route::get('/dashboard', 'SaasController@dashboard')->name('dashboard');
        Route::get('view/notice/{id}', 'SaasController@viewNotice')->name('view-notice');
        Route::get('view/admin-notice/{id}', 'SaasController@viewAdminNotice')->name('view-admin-notice');
        // update password

        // Route::get('change-password', 'SaasController@updatePassowrd')->name('updatePassowrd');
        Route::post('admin-change-password', 'SaasController@updatePassowrdStore')->name('updatePassowrdStore'); //InfixPro Version

        Route::get('administrator/institution-list', 'InstituteController@institution_List')->name('administrator/institution-list');

        Route::get('administrator/institution-create', 'InstituteController@institutionCreate')->name('administrator/institution-create');
        Route::post('administrator/institution-store', 'InstituteController@institutionStore')->name('administrator/institution-store');
        Route::get('administrator/institution-edit/{id}', 'InstituteController@institutionEdit')->name('administrator/institution-edit');
        Route::post('administrator/institution-update', 'InstituteController@institutionUpdate')->name('administrator/institution-update');
        Route::get('administrator/institution-delete/{id}', 'InstituteController@institutionDelete')->name('administrator/institution-delete');


        Route::get('institution-enable', 'InstituteController@institutionEnable');
        Route::get('institution-approve', 'InstituteController@institutionApprove');

        Route::get('administrator/institution-details/{id}', 'InstituteController@InstitutionDetails')->name('administrator/institution-details');


        //Communicate

        Route::get('administrator/send-mail', 'SaasCommunicateController@sendMail')->name('administrator/send-mail');
        Route::post('administrator/send-email', 'SaasCommunicateController@sendMailPost')->name('administrator/send-email');

        Route::get('administrator/send-sms', 'SaasCommunicateController@sendSms')->name('administrator/send-sms');



        Route::get('administrator/send-notice', 'SaasCommunicateController@sendNotice')->name('administrator/send-notice');
        Route::get('administrator/add-notice', 'SaasCommunicateController@addNotice')->name('administrator/add-notice')->middleware('userRolePermission:288');
        Route::post('administrator/save-notice', 'SaasCommunicateController@saveNotice')->name('administrator/save-notice');
        Route::get('administrator/edit-notice/{id}', 'SaasCommunicateController@editAdministratorNotice')->name('administrator/edit-notice')->middleware('userRolePermission:289');
        Route::post('administrator/update-notice', 'SaasCommunicateController@updateAdministratorNotice')->name('administrator/update-notice')->middleware('userRolePermission:289');

        Route::get('administrator/delete-notice/{id}', 'SaasCommunicateController@deleteAdministratorNotice')->name('administrator/delete-notice');


        //Report
        Route::get('administrator/student-list', 'SaasSuperadminReportController@studentList')->name('administrator/student-list');
        Route::post('administrator/student-list', 'SaasSuperadminReportController@studentListSearch')->name('administrator/student-list');

        Route::get('administrator/income-expense', 'SaasSuperadminReportController@incomeExpense')->name('administrator/income-expense');
        Route::post('administrator/income-expense', 'SaasSuperadminReportController@incomeExpenseSearch')->name('administrator/income-expense');


        Route::get('administrator/ajax-get-class', 'SaasSuperadminReportController@ajaxGetClass');


        Route::get('administrator/teacher-list', 'SaasSuperadminReportController@teacherList')->name('administrator/teacher-list');
        Route::post('administrator/teacher-list', 'SaasSuperadminReportController@teacherListSearch')->name('administrator/teacher-list');


        Route::get('administrator/class-list', 'SaasSuperadminReportController@classList')->name('administrator/class-list');
        Route::post('administrator/class-list', 'SaasSuperadminReportController@classListSearch')->name('administrator/class-list');


        Route::get('administrator/class-routine', 'SaasSuperadminReportController@classRoutine')->name('administrator/class-routine');
        Route::post('administrator/class-routine', 'SaasSuperadminReportController@classRoutineSearch')->name('administrator/class-routine');


        Route::get('administrator/staff-attendance', 'SaasSuperadminReportController@staffAttendance')->name('administrator/staff-attendance');
        Route::post('administrator/staff-attendance', 'SaasSuperadminReportController@staffAttendanceSearch')->name('administrator/staff-attendance');

        Route::get('administrator/student-attendance', 'SaasSuperadminReportController@studentAttendance')->name('administrator/student-attendance');
        Route::post('administrator/student-attendance', 'SaasSuperadminReportController@studentAttendanceSearch')->name('administrator/student-attendance');

        //system settings

        //Settings
        Route::get('administrator/general-settings', 'SaasSystemSettingController@generalSettingsView')->name('administrator/general-settings');
        Route::get('administrator/lms-settings', 'SaasSystemSettingController@lmsModuleSettings')->name('administrator/lms-settings');
        Route::get('administrator/university-settings', 'SaasSystemSettingController@universityModuleSettings')->name('administrator/university-settings');
        Route::get('administrator/custom-domain-settings', 'SaasSystemSettingController@customDomainSettingsView')->name('administrator/custom-domain-settings');
        Route::post('administrator/custom-domain-settings', 'SaasSystemSettingController@customDomainSettingsPost');
        Route::get('administrator/add-general-settings', 'SaasSystemSettingController@addGeneralSettings')->name('administrator/add-general-settings');
        Route::get('administrator/update-general-settings', 'SaasSystemSettingController@updateGeneralSettings')->name('administrator/update-general-settings');
        Route::post('administrator/update-general-settings-data', 'SaasSystemSettingController@updateGeneralSettingsData')->name('administrator/update-general-settings-data');
        Route::post('administrator/add-general-settings-data', 'SaasSystemSettingController@addGeneralSettingsData')->name('administrator/add-general-settings-data');
        Route::post('administrator/update-school-logo', 'SaasSystemSettingController@updateSchoolLogo');

        //Email Settings
        Route::get('administrator/email-settings', 'SaasSystemSettingController@emailSettings')->name('administrator/email-settings');
        Route::post('administrator/add-email-settings-data', 'SaasSystemSettingController@addEmailSettingsData')->name('administrator/add-email-settings-data');
        Route::post('administrator/update-email-settings-data', 'SaasSystemSettingController@updateEmailSettingsData');


        // // payment Method Settings
        // Route::get('administrator/payment-method-settings', 'SaasSystemSettingController@paymentMethodSettings');
        // Route::post('administrator/update-paypal-data', 'SaasSystemSettingController@updatePaypalData');
        // Route::post('administrator/update-stripe-data', 'SaasSystemSettingController@updateStripeData');
        // Route::post('administrator/update-payumoney-data', 'SaasSystemSettingController@updatePayumoneyData');
        // Route::post('administrator/active-payment-gateway', 'SaasSystemSettingController@activePaymentGateway');

        // // payment Method Settings
        // Route::post('administrator/update-payment-gateway', 'SaasSystemSettingController@updatePaymentGateway');
        // Route::post('administrator/is-active-payment', 'SaasSystemSettingController@isActivePayment');


        //ajax theme change
        Route::get('administrator/theme-style-active', 'SaasSystemSettingController@themeStyleActive');
        Route::get('administrator/theme-style-rtl', 'SaasSystemSettingController@themeStyleRTL');


        Route::get('administrator/manage-currency', 'SaasSystemSettingController@manageCurrency')->name('administrator/manage-currency');
        Route::post('administrator/currency-store', 'SaasSystemSettingController@storeCurrency')->name('administrator/currency-store');
        Route::post('administrator/currency-update', 'SaasSystemSettingController@storeCurrencyUpdate')->name('administrator/currency-update');
        Route::get('administrator/manage-currency/edit/{id}', 'SaasSystemSettingController@manageCurrencyEdit')->name('currency_edit');
        Route::get('administrator/manage-currency/delete/{id}', 'SaasSystemSettingController@manageCurrencyDelete')->name('currency_delete');

        Route::get('administrator/custom-links', 'SaasSystemSettingController@customLinks');
        Route::post('administrator/custom-links-update', 'SaasSystemSettingController@customLinksUpdate')->name('administrator/custom-links-update');


        // admin-home-page
        Route::get('administrator/admin-home-page', 'SaasSystemSettingController@homePageBackend');
        Route::post('administrator/admin-home-page-update', 'SaasSystemSettingController@homePageUpdate')->name('administrator/admin-home-page-update');


        // admin-home-page
        Route::get('administrator/admin-data-delete', 'SaasSystemSettingController@tableEmpty')->name('administrator/admin-data-delete');
        Route::post('administrator/database-delete', 'SaasSystemSettingController@databaseDelete')->name('administrator/database-delete');
        Route::get('administrator/database-restore', 'SaasSystemSettingController@databaseRestory')->name('administrator/database-restore');
        Route::post('administrator/database-restore', 'SaasSystemSettingController@databaseRestory');


        //sass
        Route::get('administrator/institution-register', 'SaasSystemSettingController@institution_register')->name('administrator/institution-register');
        Route::get('administrator/institution-register-two', 'SaasSystemSettingController@institution_register_two');
        Route::post('administrator/institution-register', 'SaasSystemSettingController@institution_register_store');


        // Route::get('administrator/institution-register-new', 'SaasSystemSettingController@institution_register_new');
        // Route::post('administrator/institution-register-store', 'SaasSystemSettingController@institutionNewStore')->name('administrator/institution-register-store');

        //Update System
        Route::get('administrator/update-system', 'SaasSystemSettingController@UpdateSystem')->name('administrator/update-system');
        Route::post('administrator/admin/update-system', 'SaasSystemSettingController@admin_UpdateSystem');
        Route::any('administrator/upgrade-settings', 'SaasSystemSettingController@UpgradeSettings');

        //Language Setting
        Route::get('administrator/language-setup/{id}', 'SaasSystemSettingController@languageSetup')->name('administrator/language-setup');
        Route::get('administrator/language-settings', 'SaasSystemSettingController@languageSettings')->name('administrator/language-settings');
        Route::post('administrator/language-add', 'SaasSystemSettingController@languageAdd')->name('administrator/language-add');

        Route::get('administrator/language-edit/{id}', 'SaasSystemSettingController@languageEdit')->name('administrator/language-edit');
        Route::post('administrator/language-update', 'SaasSystemSettingController@languageUpdate')->name('administrator/language-update');

        Route::post('administrator/language-delete', 'SaasSystemSettingController@languageDelete')->name('administrator/language-delete');

        Route::get('administrator/get-translation-terms', 'SaasSystemSettingController@getTranslationTerms');
        Route::post('administrator/translation-term-update', 'SaasSystemSettingController@translationTermUpdate')->name('translation-term-update');

        // Base group
        Route::get('administrator/base-group', ['as' => 'base_group', 'uses' => 'SaasBaseGroupController@index']);
        Route::post('administrator/base-group-store', ['as' => 'base_group_store', 'uses' => 'SaasBaseGroupController@store']);
        Route::get('administrator/base-group-edit/{id}', ['as' => 'administrator_base_group_edit', 'uses' => 'SaasBaseGroupController@edit']);
        Route::post('administrator/base-group-update', ['as' => 'base_group_update', 'uses' => 'SaasBaseGroupController@update']);
        Route::get('administrator/base-group-delete/{id}', ['as' => 'administrator_base_group_delete', 'uses' => 'SaasBaseGroupController@delete']);

        // Base setup
        Route::get('administrator/base-setup', ['as' => 'base_setup', 'uses' => 'SaasBaseSetupController@index']);
        Route::post('administrator/base-setup-store', ['as' => 'base_setup_store', 'uses' => 'SaasBaseSetupController@store']);
        Route::get('administrator/base-setup-edit/{id}', ['as' => 'base_setup_edit', 'uses' => 'SaasBaseSetupController@edit']);
        Route::post('administrator/base-setup-update', ['as' => 'base_setup_update', 'uses' => 'SaasBaseSetupController@update']);
        Route::post('administrator/base-setup-delete', ['as' => 'base_setup_delete', 'uses' => 'SaasBaseSetupController@delete']);

        //Backup Setting
        Route::post('administrator/backup-store', 'SaasSystemSettingController@BackupStore');
        Route::get('administrator/backup-settings', 'SaasSystemSettingController@backupSettings')->name('administrator/backup-settings');
        Route::get('administrator/get-backup-files/{id}', 'SaasSystemSettingController@getfilesBackup');
        Route::get('administrator/get-backup-db', 'SaasSystemSettingController@getDatabaseBackup');
        Route::get('administrator/download-database/{id}', 'SaasSystemSettingController@downloadDatabase');
        Route::get('administrator/download-files/{id}', 'SaasSystemSettingController@downloadFiles');
        Route::get('administrator/restore-database/{id}', 'SaasSystemSettingController@restoreDatabase');
        Route::get('administrator/delete-database/{id}', 'SaasSystemSettingController@deleteDatabase')->name('delete_database');

        //api seting
        Route::get('administrator/api/permission', 'SaasSystemSettingController@apiPermission')->name('administrator/api/permission');
        Route::get('api-permission-update', 'SaasSystemSettingController@apiPermissionUpdate');

        // superadmin module permission
        Route::get('administrator/module-permission', 'SaasRoleController@schoolModulePermission')->name('administrator/module-permission');
        Route::get('administrator/assign-module-permission/{id}', 'SaasRoleController@schoolAssignModulePermission')->name('assign-module-permission');
        Route::post('administrator/module-permission-store', 'SaasRoleController@schoolAssignModulePermissionStore')->name('administrator/module-permission-store');

        // background setting
        Route::get('administrator/background-setting', 'SaasBackgroundController@index')->name('administrator/background-setting');
        Route::post('administrator/background-settings-update', 'SaasBackgroundController@backgroundSettingsUpdate')->name('administrator/background-settings-update');
        Route::post('administrator/background-settings-store', 'SaasBackgroundController@backgroundSettingsStore')->name('administrator/background-settings-store');
        Route::get('administrator/background-setting-delete/{id}', 'SaasBackgroundController@backgroundSettingsDelete')->name('administrator/background-setting-delete');
        Route::get('administrator/background_setting-status/{id}', 'SaasBackgroundController@backgroundSettingsStatus')->name('administrator/background_setting-status');

        // background setting
        Route::get('school-background-setting', 'SaasBackgroundController@schoolIndex');
        Route::post('school-background-settings-update', 'SaasBackgroundController@schoolBackgroundSettingsUpdate')->name('school-background-settings-update');
        Route::post('school-background-settings-store', 'SaasBackgroundController@schoolBackgroundSettingsStore')->name('school-background-settings-store');
        Route::get('school-background-setting-delete/{id}', 'SaasBackgroundController@schoolBackgroundSettingsDelete')->name('school-background-setting-delete');
        Route::get('school-background_setting-status/{id}', 'SaasBackgroundController@schoolBackgroundSettingsStatus')->name('school-background_setting-status');

        //color theme change
        Route::get('administrator/color-style', 'SaasBackgroundController@colorTheme')->name('administrator/color-style');
        Route::get('administrator/make-default-theme/{id}', 'SaasBackgroundController@colorThemeSet')->name('administrator/make-default-theme');

        // ticket sytem
        // category
        Route::get('ticket-category', 'SaasTicketCategoryController@index')->name('ticket.category')->middleware('userRolePermission:ticket.category');
        Route::post('ticket-category', 'SaasTicketCategoryController@store')->name('ticket.category_store')->middleware('userRolePermission:ticket.category_store');
        Route::get('ticket-category-edit/{id}', 'SaasTicketCategoryController@edit')->name('ticket.category_edit')->middleware('userRolePermission:ticket.category_edit');
        Route::post('ticket-category-update/{id}', 'SaasTicketCategoryController@update')->name('ticket.category_update')->middleware('userRolePermission:ticket.category_edit');
        Route::get('ticket-category-delete-view/{id}', 'SaasTicketCategoryController@category_delete_view')->name('ticket.category_delete_view')->middleware('userRolePermission:ticket.category_delete');
        Route::get('ticket-category-delete/{id}', 'SaasTicketCategoryController@destroy')->name('ticket.category_delete')->middleware('userRolePermission:ticket.category_delete');

        // priority
        Route::get('ticket-priority', 'SaasTicketPriorityController@index')->name('ticket.priority')->middleware('userRolePermission:ticket.priority');
        Route::post('ticket-priority', 'SaasTicketPriorityController@store')->name('ticket.priority_store')
        ->middleware('userRolePermission:ticket.priority_store');
        Route::get('ticket-priority-edit/{id}', 'SaasTicketPriorityController@edit')->name('ticket.priority_edit')->middleware('userRolePermission:ticket.priority_edit');
        Route::post('ticket-priority-update/{id}', 'SaasTicketPriorityController@update')->name('ticket.priority_update')->middleware('userRolePermission:ticket.priority_edit');
        Route::get('ticket-priority-delete-view/{id}', 'SaasTicketPriorityController@priority_delete_view')->name('ticket.priority_delete_view')->middleware('userRolePermission:ticket.priority_delete_view');
        Route::get('ticket-priority-delete/{id}', 'SaasTicketPriorityController@destroy')->name('ticket.priority_delete')->middleware('userRolePermission:ticket.priority_delete');

        //ticket
        Route::get('admin/ticket-view/', 'SaasTicketController@index')->name('admin.ticket_list')->middleware('userRolePermission:school/ticket-view');
        route::post('admin/ticket-view/', 'SaasTicketController@ticket_search')->name('admin.ticket_search');
        Route::get('admin/un-assign-ticket', 'SaasTicketController@unAssignTicket')->name('admin.un_assign_ticket_list')->middleware('userRolePermission:school/ticket-unassign-list');
        Route::post('admin/un-assign-ticket', 'SaasTicketController@unAssignTicketSearch')->name('admin.un_assign_ticket_list.search');
        Route::post('admin/assign-ticket', 'AssignTicketController@store')->name('admin.assign_ticket')->middleware('userRolePermission:admin.assign_ticket');
        // Route::get('school/ticket-view/', 'SaasTicketController@index')->name('school.ticket_list');
        route::get('admin/ticket-view/{id}', 'SaasTicketController@ticket_view')->name('admin.ticket_view');

        route::get('admin/ticket-view-attachment/{id}', 'SaasTicketController@viewTicketModal')->name('admin.ticket-view-attachment');
        route::get('admin/comment-view-attachment/{id}', 'SaasTicketController@viewCommentAttachmentModal')->name('admin.comment-view-attachment');
        route::post('admin/attachment-delete', 'SaasTicketController@deleteAttachment')->name('admin.attachment-delete');

        route::get('admin/add-ticket', 'SaasTicketController@add_ticket')->name('admin.add_ticket')
        ->middleware('userRolePermission:1926');
        route::post('admin/ticket-store', 'SaasTicketController@ticket_store')->name('admin.ticket_store')->middleware('userRolePermission:admin.ticket_store');
        route::get('admin/ticket-edit/{id}', 'SaasTicketController@ticket_edit')->name('admin.ticket_edit')->middleware('userRolePermission:1927');
        route::post('admin/ticket-update/{id}', 'SaasTicketController@ticket_update')->name('admin.ticket_update')->middleware('userRolePermission:1927');
        route::get('admin/ticket-delete-view/{id}', 'SaasTicketController@ticket_delete_view')->name('admin.ticket_delete_view')->middleware('userRolePermission:1928');
        route::get('admin/ticket-delete/{id}', 'SaasTicketController@ticket_delete')->name('admin.ticket_delete')->middleware('userRolePermission:1928');
        

        //School Ticket
        Route::get('school/ticket-view', 'SaasTicketController@index')->name('school/ticket-view')->middleware('userRolePermission:school/ticket-view');
        Route::get('school/ticket-unassign-list', 'SaasTicketController@unAssignTicket')->name('school/ticket-unassign-list')->middleware('userRolePermission:school/ticket-unassign-list');
        route::get('school/add-ticket', 'SaasTicketController@add_ticket')->name('school.add_ticket')->middleware('userRolePermission:admin.ticket_store');
        route::get('school/my-ticket', 'SaasTicketController@my_ticket')->name('school.my_ticket');

        Route::get('school/ticket/open', 'SaasTicketController@openTicket')->name('ticket.open_ticket');
        // Route::get('school/ticket/list','SaasTicketController@ticketList')->name('school.ticket_list');

        //comment
        Route::post('admin/comment-store', 'SaasTicketController@comment_store')->name('admin.comment_store');
        Route::post('admin/comment-reply', 'SaasTicketController@comment_reply')->name('admin.comment_reply');
        Route::get('download-file/{id}', 'SaasTicketController@download_file')->name('download_file');


        route::get('tickets', 'SaasUserController@tickets')->name('user.ticket');
        route::get('ticket-view/{id}', 'SaasUserController@ticket_view')->name('user.ticket_view');
        route::get('add-ticket', 'SaasUserController@add_ticket')->name('user.add_ticket');
        route::post('ticket-store', 'SaasUserController@ticket_store')->name('user.ticket_store');
        route::get('ticket-edit/{id}', 'SaasUserController@ticket_edit')->name('user.ticket_edit');
        route::post('ticket-update/{id}', 'SaasUserController@ticket_update')->name('user.ticket_update');
        route::get('ticket-delete-view/{id}', 'SaasUserController@ticket_delete_view')->name('user.ticket_delete_view');
        route::get('ticket-delete/{id}', 'SaasUserController@ticket_delete')->name('user.ticket_delete');
        route::get('ticket-reopen/{id}', 'SaasUserController@reopen_ticket')->name('user.reopen_ticket');
        route::get('ticket-active', 'SaasUserController@active_ticket')->name('user.active_ticket');
        route::get('ticket-complete', 'SaasUserController@complete_ticket')->name('user.completed_ticket');

        //comment
        Route::post('comment-store', 'SaasUserController@comment_store')->name('user.comment_store');
        Route::post('comment-reply', 'SaasUserController@comment_reply')->name('user.comment_reply');


        // merit list Report
        Route::get('administrator/merit-list-report', 'SaasExamReportController@administratorMeritListReport')->name('administrator/merit-list-report');
        Route::post('administrator/merit-list-report', 'SaasExamReportController@administratorMeritListReportSearch')->name('administrator/merit-list-report');

        Route::post('administrator/merit-list/print', 'SaasExamReportController@administratorMeritListPrint')->name('administrator/merit-list/print');

        Route::get('administrator/ajax-get-class-exam', 'SaasExamReportController@ajaxGetClassExam');


        // Route::get('administrator/tabulation-sheet-report', 'SaasExamReportController@administratorTabulationSheetReport');
        // Route::post('administrator/tabulation-sheet-report', 'SaasExamReportController@administratorTabulationSheetReportSearch');
        // Route::post('administrator/tabulation-sheet/print', 'SaasExamReportController@administratorTabulationSheetReportPrint');


        Route::get('administrator/progress-card-report', 'SaasExamReportController@administratorProgressCardReport')->name('administrator/progress-card-report');
        Route::post('administrator/progress-card-report', 'SaasExamReportController@administratorProgressCardReportSearch')->name('saas_progress_card_report');

        Route::post('administrator/progress-card/print', 'SaasExamReportController@administratorProgressCardReportPrint')->name('saas_progress_card_report_print');

        //tabulation sheet report
        Route::get('administrator/reports-tabulation-sheet', ['as' => 'saas_reports_tabulation_sheet', 'uses' => 'SaasExamReportController@reportsTabulationSheet']);
        Route::post('administrator/reports-tabulation-sheet', ['as' => 'saas_reports_tabulation_sheet', 'uses' => 'SaasExamReportController@reportsTabulationSheetSearch']);
        Route::post('administrator/tabulation-sheet/print', 'SaasExamReportController@administratorTabulationSheetReportPrint');


        // mark sheet Report
        Route::get('administrator/mark-sheet-report', ['as' => 'saas_mark_sheet_report', 'uses' => 'SaasExamReportController@markSheetReport']);
        Route::post('administrator/mark-sheet-report', ['as' => 'saas_mark_sheet_report', 'uses' => 'SaasExamReportController@markSheetReportSearch']);


        //mark sheet report student
        Route::get('administrator/mark-sheet-report-student', ['as' => 'saas_mark_sheet_report_student', 'uses' => 'SaasExamReportController@markSheetReportStudent']);
        Route::post('administrator/mark-sheet-report-student', ['as' => 'saas_mark_sheet_report_student', 'uses' => 'SaasExamReportController@markSheetReportStudentSearch']);

        Route::get('administrator/mark-sheet-report/print/{exam_id}/{class_id}/{section_id}/{student_id}', ['as' => 'saas_mark_sheet_report', 'uses' => 'SaasExamReportController@markSheetReportStudentPrint']);


        // Route::get('school-general-settings', 'SaasSystemSettingController@schoolSettingsView');
        // Route::post('update-logo', 'SaasSystemSettingController@updateLogo');
        Route::get('school-general-settings', 'SaasSystemSettingController@schoolSettingsView')->name('school-general-settings')->middleware('userRolePermission:405');
        Route::post('update-logo', 'SaasSystemSettingController@updateLogo')->name('update-logo');

        Route::get('update-school-settings', 'SaasSystemSettingController@updateSchoolSettings')->name('update-school-settings');
        Route::post('update-school-settings-data', 'SaasSystemSettingController@updateSchoolSettingsData')->name('update-school-settings-data');

        // Route::resource('holiday', 'SmHolidayController');
        // Route::resource('weekend', 'SmWeekendController');
        Route::get('delete-holiday-view/{id}', 'SmHolidayController@deleteHolidayView');
        Route::get('delete-holiday/{id}', 'SmHolidayController@deleteHoliday');


        //Email Settings
        Route::get('school-email-settings', 'SaasSystemSettingController@SchoolEmailSettings');
        Route::post('school-add-email-settings-data', 'SaasSystemSettingController@schoolAddEmailSettingsData')->name('school-add-email-settings-data');
        Route::post('school-update-email-settings-data', 'SaasSystemSettingController@schoolUpdateEmailSettingsData')->name('school-update-email-settings-data');


        // payment Method Settings
        // Route::get('payment-method-settings', 'SaasSystemSettingController@paymentMethodSettings');
        Route::post('update-paypal-data', 'SaasSystemSettingController@updatePaypalData');
        Route::post('update-stripe-data', 'SaasSystemSettingController@updateStripeData');
        Route::post('update-payumoney-data', 'SaasSystemSettingController@updatePayumoneyData');
        Route::post('active-payment-gateway', 'SaasSystemSettingController@activePaymentGateway');

        // payment Method Settings
        // Route::post('update-payment-gateway', 'SaasSystemSettingController@updatePaymentGateway');
        // Route::post('is-active-payment', 'SaasSystemSettingController@isActivePayment');


        //color theme change
        Route::get('school-color-style', 'SaasBackgroundController@colorTheme');
        Route::get('school-make-default-theme/{id}', 'SaasBackgroundController@colorThemeSet')->name('school-make-default-theme');
        Route::get('saas-settings', 'SaasSettingsController@index')->name('saas-settings');
        Route::post('saas/field/switch', 'SaasSettingsController@statusChange')->name('saas-button-status');


    });

    Route::get('/custom-domain', 'CustomDomainController@index')->name('saas.custom-domain');
    Route::post('/custom-domain/validate', 'CustomDomainController@validate')->name('saas.custom-domain.validate');
    Route::post('/custom-domain/dns-check', 'CustomDomainController@dnsCheck')->name('saas.custom-domain.dns_check');
    Route::post('/custom-domain/remove', 'CustomDomainController@remove')->name('saas.custom-domain.remove');
    Route::post('/custom-domain', 'CustomDomainController@store');


    Route::prefix('subscription')->group(function () {
        Route::group(['middleware' => 'auth'], function(){
            Route::get('/', 'SaasSubscriptionController@index');
            Route::get('trial-institutions', 'SaasSubscriptionController@TrailInstitution')->name('TrailInstitution');


            Route::get('/packages', 'SaasSubscriptionController@packages')->name('subscription/packages');
            Route::post('/package-store', 'SaasSubscriptionController@packageStore')->name('subscription/package-store');
            Route::get('/package-edit/{id}', 'SaasSubscriptionController@packageEdit')->name('subscription/package-edit');
            Route::get('/package-view/{id}', 'SaasSubscriptionController@packageView')->name('subscription/package-view');
            Route::post('/package-update', 'SaasSubscriptionController@packageUpdate')->name('subscription/package-update');
            Route::get('/package-delete/{id}', 'SaasSubscriptionController@packageDelete')->name('subscription/package-delete');

            Route::get('/assign-package/{id}', 'SaasSubscriptionController@packageAssign')->name('subscription/assign-package');
            Route::get('/package-purchase-history/{id}', 'SaasSubscriptionController@purchaseHistory')->name('subscription.purchaseHistory');

            Route::get('/payment-method', 'SaasSubscriptionController@paymentMethod');
            Route::post('payment-method-store', 'SaasSubscriptionController@paymentMethodStore');
            Route::get('payment-method-edit/{id}', 'SaasSubscriptionController@paymentMethodEdit')->name('subscription/payment-method-edit');
            Route::post('payment-method-update', 'SaasSubscriptionController@paymentMethodUpdate');
            Route::get('payment-method-delete/{id}', 'SaasSubscriptionController@paymentMethodDelete')->name('subscription/payment-method-delete');

            // payment Method Settings
            Route::get('payment-method-setting', 'SaasSubscriptionController@paymentMethodSettings');
            Route::post('update-paypal-data', 'SmSystemSettingController@updatePaypalData');
            Route::post('update-stripe-data', 'SmSystemSettingController@updateStripeData');
            Route::post('update-payumoney-data', 'SmSystemSettingController@updatePayumoneyData');
            Route::post('active-payment-gateway', 'SmSystemSettingController@activePaymentGateway');

            Route::post('is-active-payment', 'SaasSubscriptionController@isActivePayment')->name('subscription/is-active-payment');
            Route::post('update-payment-gateway', 'SaasSubscriptionController@updatePaymentGateway')->name('subscription/update-payment-gateway');


            Route::get('settings', 'SaasSubscriptionController@settings')->name('subscription/settings');
            Route::post('settings', 'SaasSubscriptionController@settingsStore');

            // school end routes
            Route::get('package-list', 'SaasSubscriptionSchoolController@packageList')->name('subscription/package-list');
            Route::get('buy-now/{id}/{slug}', 'SaasSubscriptionSchoolController@buyNow')->name('subscription/buy-now');
            Route::post('make-payment', 'SaasSubscriptionSchoolController@makePayment')->name('subscription/make-payment');

            // for paystack
            Route::get('/payment/callback', 'SaasSubscriptionSchoolController@handleGatewayCallback')->name('payment/callback');

            // in school panel
            Route::get('history', 'SaasSubscriptionSchoolController@paymentHistory')->name('subscription/history');
            Route::get('download-payment-document/{file_name}', function ($file_name = null) {
                $file = base_path() . '/Modules/Saas/public/uploads/' . $file_name;

                if (file_exists($file)) {

                    return Response::download($file);
                }
            })->name('subscription/download-payment-document');

            // saas panel
            Route::get('school-payments', 'SaasSubscriptionSchoolController@schoolPayments')->name('subscription/school-payments');
            Route::get('update-status-update/{id}', 'SaasSubscriptionSchoolController@updateStatus')->name('subscription/update-status');
            Route::post('update-status-update-store', 'SaasSubscriptionSchoolController@updateStatusStore')->name('subscription/update-status-store');

            Route::get('payment-history', 'SaasSubscriptionSchoolController@SaasPaymentHistory')->name('subscription/payment-history');
            Route::get('single-school-payment/{id}', 'SaasSubscriptionSchoolController@singleSchoolPayment')->name('subscription/single-school-payment');

            //


            // Add payment by saas superadmin
            Route::get('add-payment/{id}', 'SaasSubscriptionController@addPayment')->name('subscription/add-payment');
            Route::post('store-payment', 'SaasSubscriptionController@storePayment')->name('subscription/store-payment');

            Route::get('assign-module', 'SaasSubscriptionController@assignModule')->name('subscription/assign-module');
            Route::post('assign-module', 'SaasSubscriptionController@postAssignModule');
            Route::get('add-module/{id}', 'SaasSubscriptionController@addModule')->name('subscription/add-module');
            Route::post('add-module/{id}', 'SaasSubscriptionController@storeModule');
        });

        Route::get('/paypal-return-status', 'SaasSubscriptionSchoolController@getPaymentStatus')->name('subscription.paypal-return-status');
        Route::get('get-package-info', 'SaasSubscriptionSchoolController@getPackageInfo');

    });


    Route::prefix('saassubscription')->group(function () {
        Route::get('about', 'SaasSubscriptionController@about');
    });
});