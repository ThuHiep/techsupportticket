<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guest\HomepageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatisticalController;

Route::get('/', function () {
    return view('home'); // Đổi 'home' thành file view có sẵn
});

/*Route login cua admin*/
Route::get('admin/login', [AuthController::class, 'login'])->name('auth.login');

/*Route register cua user*/
Route::get('/forgot_pass_admin', [AuthController::class, 'showForgotPass'])->name('forgot_pass_admin');

/*Route dashboard cho admin*/
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

/*Route hompage */
Route::get('homepage', [HomepageController::class, 'login'])->name('homepage.index');


/*Route login cua user*/
Route::get('login', [LoginController::class, 'login'])->name('login.login');
/*Route register cua user*/
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
/*Route register cua user*/
Route::get('/forgot_pass', [LoginController::class, 'showForgotPass'])->name('forgot_pass');



// Nhóm route cho phần admin
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

Route::get('/admin/user/list', [UserController::class, 'getUserList'])->name('admin.user.list');
Route::post('customer/approve/{id}', [CustomerController::class, 'approveCustomer'])->name('customer.approve');



//Department
Route::name('department.')->group(function () {
    Route::get('/department/index', [DepartmentController::class, 'index'])->name('index');
    Route::get('/department/create', [DepartmentController::class, 'create'])->name('create');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('store');
    Route::get('/department/edit/{department_id}', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('/department/update/{department_id}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/department/delete/{department_id}', [DepartmentController::class, 'destroy'])->name('delete');
});

//Nhóm thống kê:
// Hiển thị danh sách khách hàngc
Route::name('statistical.')->group(function () {
    Route::get('/statistical/index', [StatisticalController::class, 'index'])->name('index');
});



