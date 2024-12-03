<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\DashboardController;
//use App\Http\Controllers\Backend\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', [AuthController::class, 'login'])->name('auth.login');

Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


//Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Hiển thị form đăng nhập
//Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process'); // Xử lý form đăng nhập

