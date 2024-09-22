<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminActivityController;

// Route cho login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Route cho logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route cho admin với middleware kiểm tra vai trò admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/students', [AdminController::class, 'showStudents'])->name('admin.students');

    Route::get('/admin/activities', [AdminActivityController::class, 'index'])->name('admin.activities.index');
    Route::get('/admin/activities/create', [AdminActivityController::class, 'create'])->name('admin.activities.create');
    Route::post('/admin/activities', [AdminActivityController::class, 'store'])->name('admin.activities.store');
    Route::get('/admin/activities/edit/{id}', [AdminActivityController::class, 'edit'])->name('admin.activities.edit');
    Route::put('/admin/activities/{id}', [AdminActivityController::class, 'update'])->name('admin.activities.update');
    Route::patch('/admin/activities/{id}/destroy-or-hide', [AdminActivityController::class, 'destroyOrHide'])->name('admin.activities.destroyOrHide');

    Route::get('/admin/managers', [AdminController::class, 'showUsers'])->name('admin.managers.index');
    Route::post('/admin/managers/{user}/role', [AdminController::class, 'updateRole'])->name('admin.managers.updateRole');
    Route::delete('/admin/managers/{user}', [AdminController::class, 'destroy'])->name('admin.managers.destroy');
});

Route::resource('registrations', RegistrationController::class);
// Route với vai trò người dùng
Route::middleware('auth')->group(function () {
    // Route cho Dashboard
    Route::get('student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    
    // Route cho Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Route cho Đăng ký hoạt động
    Route::get('/registrations/create/{id}', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('registrations/store/{id}', [RegistrationController::class, 'store'])->name('registrations.store');

    // Route cho hoạt động
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show'); // Route để hiển thị chi tiết một hoạt động cụ thể
});

// Route cho trang chính của ứng dụng (không yêu cầu auth)
Route::get('/', [HomeController::class, 'index'])->name('home'); // Giữ tên route này nếu nó đại diện cho trang chính không yêu cầu auth

