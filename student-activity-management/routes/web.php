<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\NewsController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminActivityController;
use App\Http\Controllers\AdminIssueController;
use App\Http\Controllers\AdminRegistrationController;


// Route cho login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Route cho logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route cho admin với middleware kiểm tra vai trò admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/managers/students', [AdminController::class, 'showStudents'])->name('admin.students');

    Route::get('admin/issues', [AdminIssueController::class, 'index'])->name('admin.issues.index');
    Route::get('admin/issues/{id}/resolve', [AdminIssueController::class, 'resolve'])->name('admin.issues.resolve');
    Route::get('admin/issues/send', [AdminIssueController::class, 'send'])->name('admin.issues.send');
    Route::post('admin/issues/send', [AdminIssueController::class, 'storeSend'])->name('admin.issues.storeSend');


    Route::get('/admin/activities', [AdminActivityController::class, 'index'])->name('admin.activities.index');
    Route::get('/admin/activities/create', [AdminActivityController::class, 'create'])->name('admin.activities.create');
    Route::post('/admin/activities', [AdminActivityController::class, 'store'])->name('admin.activities.store');
    Route::get('/admin/activities/edit/{id}', [AdminActivityController::class, 'edit'])->name('admin.activities.edit');
    Route::put('/admin/activities/{id}', [AdminActivityController::class, 'update'])->name('admin.activities.update');
    Route::patch('/admin/activities/{id}/destroy-or-hide', [AdminActivityController::class, 'destroyOrHide'])->name('admin.activities.destroyOrHide');
    Route::get('admin/activities/{activity_id}/registered-users', [AdminActivityController::class, 'registeredUsers'])->name('admin.activities.registered-users');

    Route::get('/admin/managers', [AdminController::class, 'showUsers'])->name('admin.managers.index');
    Route::get('/admin/managers/create', [AdminController::class, 'create'])->name('admin.managers.create');
    Route::post('admin/managers/import', [AdminController::class, 'import'])->name('admin.managers.import');
    Route::post('/admin/managers', [AdminController::class, 'store'])->name('admin.managers.store');
    Route::get('/managers/{id}/edit', [AdminController::class, 'edit'])->name('admin.managers.edit');
    Route::post('/managers/{id}', [AdminController::class, 'update'])->name('admin.managers.update');
    Route::post('/admin/managers/{user}/role', [AdminController::class, 'updateRole'])->name('admin.managers.updateRole');
    Route::delete('/admin/managers/{user}', [AdminController::class, 'destroy'])->name('admin.managers.destroy');

    Route::post('/admin/activities/{id}/registrations/import-attendance', [AdminRegistrationController::class, 'importAttendance'])
        ->name('admin.registrations.importAttendance');
    Route::get('/admin/activities/{id}/unregistered-attendances', [AdminActivityController::class, 'showUnregisteredAttendances'])
        ->name('admin.activities.unregistered-attendances');

    Route::get('/admin/news', [AdminNewsController::class, 'index'])->name('admin.news.index');
    Route::get('admin/news/create', [AdminNewsController::class, 'create'])->name('admin.news.create');
    Route::post('admin/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::get('admin/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
    Route::delete('/admin/news/{id}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');
    Route::put('admin/news/{id}', [AdminNewsController::class, 'update'])->name('admin.news.update');
});

Route::resource('registrations', RegistrationController::class);
// Route với vai trò người dùng
Route::middleware('auth')->group(function () {
    // Route cho Dashboard
    Route::get('student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    // Route cho người dùng gửi thắc mắc
    Route::post('student/issues', [IssueController::class, 'store'])->name('student.issues.store');

    // routes/web.php
    Route::get('/news', [NewsController::class, 'index'])->name('student.news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->name('student.news.show');


    Route::get('/issues', [IssueController::class, 'index'])->name('student.issues.index');

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
