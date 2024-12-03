<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Middleware\AuthenticateMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', [AuthController::class, 'login'])->name('auth.login');


Route::get('dashboard/index', [DashboardController::class, 'index'])
->name('dashboard.index')->middleware(AuthenticateMiddleware::class);

Route::get('user/index', [UserController::class, 'index'])
->name('user.index');
