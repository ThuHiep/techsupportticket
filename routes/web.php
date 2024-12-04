<?php

use App\Http\Controllers\Backend\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', [AuthController::class, 'login'])->name('auth.login');

//Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


Route::get('user/index', [UserController::class, 'index'])
->name('user.index');


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

