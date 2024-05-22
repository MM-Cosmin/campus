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

use Modules\WhatsappSupport\Http\Controllers\AgentController;
use Modules\WhatsappSupport\Http\Controllers\MessageController;
use Modules\WhatsappSupport\Http\Controllers\SettingsController;

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('whatsapp-support')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('whatsapp-support.settings');
        Route::post('settings', [SettingsController::class, 'update'])->name('whatsapp-support.settings.update');

        Route::get('agents', [AgentController::class, 'index'])->name('whatsapp-support.agents');
        Route::get('agents/create', [AgentController::class, 'create'])->name('whatsapp-support.agents.create');
        Route::post('agents/store', [AgentController::class, 'store'])->name('whatsapp-support.agents.store');
        Route::post('agents/update', [AgentController::class, 'update'])->name('whatsapp-support.agents.update');
        Route::get('agents/show/{id}', [AgentController::class, 'show'])->name('whatsapp-support.agents.show');
        Route::get('agents/delete/{id}', [AgentController::class, 'destroy'])->name('whatsapp-support.agents.delete');

        Route::post('message/send', [MessageController::class, 'send'])->name('whatsapp-support.message.send');
        Route::get('analytics', [MessageController::class, 'analytics'])->name('whatsapp-support.analytics');
    });
});
