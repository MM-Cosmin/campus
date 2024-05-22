<?php

use Illuminate\Support\Facades\Route;
use Modules\Gmeet\Http\Controllers\GmeetReportController;
use Modules\Gmeet\Http\Controllers\GmeetSettingsController;
use Modules\Gmeet\Http\Controllers\GoogleAccountController;
use Modules\Gmeet\Http\Controllers\UploadDocumentController;
use Modules\Gmeet\Http\Controllers\GmeetVirtualClassController;
use Modules\Gmeet\Http\Controllers\GmeetVirtualMeetingController;

Route::group(['prefix' => 'g-meet', 'as' => 'g-meet.', 'middleware' => ['auth']], function ($routes) {
    $routes->group(['prefix' => 'settings', 'as' => 'settings.'], function ($route) {
        $route->get('/', [GmeetSettingsController::class, 'index'])->name('index')->middleware('userRolePermission:g-meet.settings.index');
        $route->put('/{id}', [GmeetSettingsController::class, 'update'])->name('update');
    });
    $routes->group(['prefix' => 'virtual-class', 'as' => 'virtual-class.'], function ($route) {
        $route->get('/', [GmeetVirtualClassController::class, 'index'])->name('index')->middleware('userRolePermission:g-meet.virtual-class.index');
        $route->post('/store', [GmeetVirtualClassController::class, 'store'])->name('store')->middleware('userRolePermission:g-meet.virtual-class.store');
        $route->get('/{id}/edit', [GmeetVirtualClassController::class, 'edit'])->name('edit')->middleware('userRolePermission:g-meet.virtual-class.edit');
        $route->get('/{id}/show', [GmeetVirtualClassController::class, 'show'])->name('show');
        $route->put('/{id}', [GmeetVirtualClassController::class, 'update'])->name('update');
        $route->delete('/{id}', [GmeetVirtualClassController::class, 'destroy'])->name('destroy')->middleware('userRolePermission:1254');
        $route->get('virtual-class/child/{id}', [GmeetVirtualClassController::class, 'myChild'])->name('parent.virtual-class')->middleware('userRolePermission:g-meet.parent.virtual-class');
       
    });
  
    $routes->group(['prefix' => 'virtual-meeting', 'as' => 'virtual-meeting.'], function ($route) {
        $route->get('/', [GmeetVirtualMeetingController::class, 'index'])->name('index');
        $route->post('/store', [GmeetVirtualMeetingController::class, 'store'])->name('store')->middleware('userRolePermission:g-meet.virtual-meeting.index');
        $route->get('/{id}/edit', [GmeetVirtualMeetingController::class, 'edit'])->name('edit')->middleware('userRolePermission:g-meet.virtual-meeting.edit');
        $route->get('/{id}/show', [GmeetVirtualMeetingController::class, 'show'])->name('show');
        $route->put('/{id}', [GmeetVirtualMeetingController::class, 'update'])->name('update');
        $route->delete('/{id}/delete', [GmeetVirtualMeetingController::class, 'destroy'])->name('destroy')->middleware('userRolePermission:g-meet.virtual-meeting.delete');
    });

    $routes->post('upload-document', [UploadDocumentController::class, 'store'])->name('upload-document');
    $routes->get('upload-document-modal/{id}', [UploadDocumentController::class, 'modal'])->name('upload-document-modal');
    $routes->post('upload-document-destroy', [UploadDocumentController::class, 'destroy'])->name('upload-document-destroy');
    $routes->get('virtual-class-reports', [GmeetReportController::class, 'classReport'])->name('virtual.class.reports.show')->middleware('userRolePermission:g-meet.virtual.class.reports.show');
    $routes->get('virtual-meeting-reports', [GmeetReportController::class, 'meetingReport'])->name('virtual.meeting.reports.show')->middleware('userRolePermission:g-meet.virtual.meeting.reports.show');
    $routes->get('user-list-user-type-wise', [GmeetVirtualMeetingController::class, 'userWiseUserList'])->name('user.list.user.type.wise');

    Route::get('google', [GoogleAccountController::class, 'index'])->name('google.index');

    Route::get('google/{googleAccount}', [GoogleAccountController::class, 'destroy'])->name('google.destroy');

});
Route::get('gmeet/google/oauth', [GoogleAccountController::class, 'store'])->name('gmeet.google.store');

