<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\Admin\StatisticalController;

use App\Http\Controllers\guest\HomepageController;
use App\Http\Controllers\guest\LoginController;
use App\Http\Controllers\guest\UserController;
use App\Http\Controllers\admin\RequestController;
use App\Http\Controllers\admin\FAQController;
use Illuminate\Support\Facades\Route;
//Cấm đụng cái này
Route::get('/', [HomepageController::class, 'login'])->name('homepage.index');

/*Route login cua admin*/

Route::get('admin/login', [AuthController::class, 'login'])->name('auth.login');

/*Route register cua user*/
Route::get('/forgot_pass_admin', [AuthController::class, 'showForgotPass'])->name('forgot_pass_admin');

/*Route dashboard cho admin*/
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

/*Route login cua user*/
Route::get('login', [LoginController::class, 'login'])->name('login.login');
/*Route register cua user*/
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
/*Route register cua user*/
Route::get('/forgot_pass', [LoginController::class, 'showForgotPass'])->name('forgot_pass');
/*Route thay đổi mật khẩu cua user*/
Route::get('auth/changePass/{user_id}', [AuthController::class, 'changePass'])->name('auth.changePass');
/*Route update mật khẩu cua user*/
Route::put('auth/updatePass/{user_id}', [AuthController::class, 'updatePass'])->name('auth.updatePass');

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

    // Phê duyệt khách hàng
    Route::post('/customer/{customer_id}/approve', [CustomerController::class, 'approve'])->name('approve');
    Route::post('/customer/{customer_id}/reject', [CustomerController::class, 'reject'])->name('reject');

    // Hiển thị danh sách khách hàng chờ duyệt
    Route::get('/customer/pending', [CustomerController::class, 'pendingCustomers'])->name('pending');
});



//Employee
Route::name('employee.')->group(function () {
    Route::get('employee/index', [EmployeeController::class, 'index'])->name('index');
    Route::get('admin/employee', [EmployeeController::class, 'createEmployee'])->name('create');
    Route::post('admin/employee', [EmployeeController::class, 'saveEmployee'])->name('save');
    Route::get('/admin/employee/{id}', [EmployeeController::class, 'editEmployee'])->name('edit');
    Route::put('/admin/employee/{id}', [EmployeeController::class, 'updateEmployee'])->name('update');
    Route::delete('/admin/employee/{id}', [EmployeeController::class, 'deleteEmployee'])->name('delete');
});

Route::get('/admin/user/list', [UserController::class, 'getUserList'])->name('admin.user.list');





//Department Routes
Route::name('department.')->group(function () {
    Route::get('/department/index', [DepartmentController::class, 'index'])->name('index');
    Route::get('/department/create', [DepartmentController::class, 'create'])->name('create');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('store');
    Route::get('/department/edit/{department_id}', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('/department/update/{department_id}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/department/delete/{department_id}', [DepartmentController::class, 'destroy'])->name('delete');
});

// Request Routes
Route::name('request.')->group(function () {
    Route::get('/request/index', [RequestController::class, 'index'])->name('index');
    Route::get('/request/create', [RequestController::class, 'create'])->name('create');
    Route::post('/request/store', [RequestController::class, 'store'])->name('store');
    Route::get('/request/edit/{request_id}', [RequestController::class, 'edit'])->name('edit');
    Route::put('/request/update/{request_id}', [RequestController::class, 'update'])->name('update');
    Route::delete('/request/delete/{request_id}', [RequestController::class, 'destroy'])->name('delete');
});

//Nhóm thống kê:
// Hiển thị danh sách khách hàngc
Route::name('statistical.')->group(function () {
    Route::get('/statistical/index', [StatisticalController::class, 'index'])->name('index');
});





// FAQ Routes
Route::name('faq.')->group(function () {
    Route::get('/faq/index', [FaqController::class, 'index'])->name('index');
});
