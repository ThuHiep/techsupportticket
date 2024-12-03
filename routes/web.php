<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->name('auth.login');

Route::get('users', [UserController::class, 'index'])->name('users.index');

