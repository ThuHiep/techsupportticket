<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\HomepageController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\UserController;


Route::get('/', function () {
    return view('home'); // Đổi 'home' thành file view có sẵn
});


/*Route login cua admin*/
Route::get('admin', [AuthController::class, 'login'])->name('auth.login');

/*Route hompage */
Route::get('homepage', [HomepageController::class, 'login'])->name('homepage.home.index');


/*Route login cua user*/
Route::get('login', [LoginController::class, 'login'])->name('login.login');
/*Route register cua user*/
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
/*Route register cua user*/
Route::get('/forgot_pass', [LoginController::class, 'showForgotPass'])->name('forgot_pass');
/*Route dashboard cho admin*/
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


// Nhóm route cho phần Backend
Route::name('customer.')->group(function () {
    // Hiển thị danh sách khách hàng
    Route::get('/customer/index', [CustomerController::class, 'index'])->name('index');

    // Hiển thị form tạo khách hàng mới
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('create');

    // Lưu khách hàng mới
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('store');

    // Chỉnh sửa khách hàng
    Route::get('/customer/edit/{customer_id}', [CustomerController::class, 'edit'])->name('edit');

    // Route cho cập nhật khách hàng
    Route::put('/customer/update/{customer_id}', [CustomerController::class, 'update'])->name('update');

    // Xóa khách hàng
    Route::delete('/customer/delete/{customer_id}', [CustomerController::class, 'destroy'])->name('delete');
});

Route::get('/backend/user/list', [UserController::class, 'getUserList'])->name('backend.user.list');


