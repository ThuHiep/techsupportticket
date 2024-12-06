<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\UserController;

Route::get('/', function () {
    return view('home'); // Đổi 'home' thành file view có sẵn
});


/*Route login cua admin*/
Route::get('admin', [AuthController::class, 'login'])->name('auth.login');



/*Route login cua user*/
Route::get('login', [LoginController::class, 'login'])->name('login.login');
/*Route register cua user*/
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
/*Route register cua user*/
Route::get('/forgot_pass', [LoginController::class, 'showForgotPass'])->name('forgot_pass');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');




// Nhóm route cho phần Backend
// Định nghĩa route cho phần backend nhưng hiển thị URL là 'customer/hienthi'
Route::prefix('backend')->name('backend.')->group(function () {
    // Hiển thị danh sách khách hàng
    Route::get('/customer/index', [CustomerController::class, 'index'])->name('customer.index');

    // Hiển thị form tạo khách hàng mới
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');

    // Lưu khách hàng mới
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');

    // Chỉnh sửa khách hàng
    Route::get('/customer/edit/{customer_id}', [CustomerController::class, 'edit'])->name('customer.edit');

    // Route cho cập nhật khách hàng
    Route::put('/customer/update/{customer_id}', [CustomerController::class, 'update'])->name('customer.update');

    // Xóa khách hàng
    Route::delete('/customer/delete/{customer_id}', [CustomerController::class, 'destroy'])->name('customer.delete');
});

// Định nghĩa route riêng cho customer mà không có tiền tố 'backend'
Route::get('/customer/index', function () {
    return redirect()->route('backend.customer.index');
});
Route::get('/backend/user/list', [UserController::class, 'getUserList'])->name('backend.user.list');


