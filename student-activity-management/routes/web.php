<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('students', StudentController::class);
Route::resource('activities', ActivityController::class);
Route::resource('registrations', RegistrationController::class);

