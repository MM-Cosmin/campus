<?php

use Illuminate\Http\Request;
use Modules\BBB\Http\Controllers\Api\BbbApiController;

Route::middleware(['auth:api', 'subdomain'])->group(function () {
    Route::get('bbb/virtual-class/{record_id}', [BbbApiController::class, 'index']);
    Route::get('bbb/meetings', [BbbApiController::class, 'meetings']);
    Route::get('bbb/meeting-join/{id}', [BbbApiController::class, 'meetingJoin']);
});