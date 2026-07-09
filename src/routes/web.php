<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AdminLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// ===== 一般ユーザー =====

Route::middleware('auth')->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);

    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);

    Route::post('/attendance/break-in',[AttendanceController::class, 'breakIn']);

    Route::post('/attendance/break-out',[AttendanceController::class, 'breakOut']);

    Route::get('/attendance/list', function () {
        return view('attendance.list');
    });

    Route::get('/attendance/detail', function () {
        return view('attendance.detail');
    });

    Route::get('/attendance/detail/approved', function () {
        return view('attendance.detail-approved');
    });

    Route::get('/request/list', function () {
        return view('request.list');
    });

    Route::get('/request/detail', function () {
        return view('request.detail');
    });

    Route::get('/report', function () {
        return view('report.index');
    });
});

Route::get('/register', function () {
    return view('auth.register');
});

// ===== 管理者 =====


Route::get('/admin/attendance/list', function () {
    return view('admin.attendance-list');
});

Route::get('/admin/attendance/detail', function () {
    return view('admin.attendance-detail');
});

Route::get('/admin/staff/list', function () {
    return view('admin.staff-list');
});

Route::get('/admin/staff/attendance/list', function () {
    return view('admin.staff-attendance-list');
});

Route::get('/admin/request/list', function () {
    return view('admin.request-list');
});

Route::get('/admin/request/detail', function () {
    return view('admin.request-detail');
});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm']);

Route::post('/admin/login', [AdminLoginController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::post('/admin/logout', function (Request $request) {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');

    })->name('admin.logout');

});

