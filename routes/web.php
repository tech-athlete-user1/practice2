<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StampController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DetailController;

Route::get('/', function () {
    return redirect()->route('stamp');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // 打刻ページ
    Route::get('/stamp', [StampController::class, 'index'])->name('stamp');
    // 打刻実行 (POST)
    Route::post('/stamp/punch', [StampController::class, 'punch'])->name('stamp.punch');
    // 今日の状態取得 (API)
    Route::get('/api/attendance/today', [StampController::class, 'getTodayStatus'])->name('api.today');

    // 他のページ
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/application', [ApplicationController::class, 'index'])->name('application');
    Route::get('/detail', [DetailController::class, 'index'])->name('detail');
});