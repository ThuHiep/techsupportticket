<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', [AuthController::class, 'login'])->name('auth.login');

Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


Route::get('user/index', [UserController::class, 'index'])
->name('user.index');