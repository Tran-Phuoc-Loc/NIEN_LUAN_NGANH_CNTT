<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;

// Route cho login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Route cho logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Routes cho admin và user



Route::middleware('auth')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('student/dashboard', [StudentController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
});


// Route cho trang chính của ứng dụng (không yêu cầu auth)
Route::get('/', [HomeController::class, 'index'])->name('home'); // Giữ tên route này nếu nó đại diện cho trang chính không yêu cầu auth

// Các route khác
Route::resource('students', StudentController::class);
Route::resource('activities', ActivityController::class);
Route::resource('registrations', RegistrationController::class);
