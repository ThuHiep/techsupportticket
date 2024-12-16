<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\RequestController;
use App\Http\Controllers\admin\FAQController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\guest\HomepageController;
use App\Http\Controllers\guest\UserController;

use App\Http\Controllers\login\AuthController;
use Illuminate\Support\Facades\Route;
//Cấm đụng cái này
Route::get('/', [HomepageController::class, 'login'])->name('homepage.index');

/*Route dashboard cho admin*/
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('customersp')->name('dashboard.index');

/*Route login*/
Route::get('login', [AuthController::class, 'login'])->name('login'); // mới sửa
Route::post('/loginProcess', [AuthController::class, 'LoginProcess'])->name('loginProcess');
/*Route logout*/
Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
/*Route register cua user*/
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/registerProcess', [AuthController::class, 'registerProcess'])->name('registerProcess');
/*Router xử lý ForgotPass*/
Route::get('/forgotPass', [AuthController::class, 'forgotPass'])->name('forgotPass');
Route::post('/forgotPassProcess', [AuthController::class, 'forgotPassProcess'])->name('forgotPassProcess');

Route::get('/verifyOTP/{user_id}', [AuthController::class, 'verifyOTP'])->name('verifyOTP');
Route::post('/verifyOTPProcess/{user_id}', [AuthController::class, 'verifyOTPProcess'])->name('verifyOTPProcess');

Route::get('/changePass/{user_id}', [AuthController::class, 'changePass'])->name('changePass');
Route::put('/updatePass/{user_id}', [AuthController::class, 'updatePass'])->name('updatePass');

/*Route thay đổi mật khẩu từ email*/
Route::get('/changePassEmail/{user_id}', [AuthController::class, 'changePassEmail'])->name('changePassEmail');
/*Route update mật khẩu từ email*/
Route::put('/updatePassEmail/{user_id}', [AuthController::class, 'updatePassEmail'])->name('updatePassEmail');

// Nhóm route cho phần admin
Route::name('customer.')->group(function () {
    // Hiển thị danh sách khách hàng
    Route::get('/customer/index', [CustomerController::class, 'index'])->middleware('customersp')->name('index');

    // Hiển thị form tạo khách hàng mới
    Route::get('/customer/create', [CustomerController::class, 'create'])->middleware('customersp')->name('create');

    // Lưu khách hàng mới
    Route::post('/customer/store', [CustomerController::class, 'store'])->middleware('customersp')->name('store');

    // Chỉnh sửa khách hàng
    Route::get('/customer/edit/{customer_id}', [CustomerController::class, 'edit'])->middleware('customersp')->name('edit');

    // Route cho cập nhật khách hàng
    Route::put('/customer/update/{customer_id}', [CustomerController::class, 'update'])->middleware('customersp')->name('update');

    // Xóa khách hàng
    Route::delete('/customer/delete/{customer_id}', [CustomerController::class, 'destroy'])->middleware('customersp')->name('delete');

    // Phê duyệt khách hàng
    Route::post('/customer/{customer_id}/approve', [CustomerController::class, 'approve'])->middleware('customersp')->name('approve');
    Route::post('/customer/{customer_id}/reject', [CustomerController::class, 'reject'])->middleware('customersp')->name('reject');

    // Hiển thị danh sách khách hàng chờ duyệt
    Route::get('/customer/pending', [CustomerController::class, 'pendingCustomers'])->middleware('customersp')->name('pending');


});



//Employee
Route::name('employee.')->group(function () {
    Route::get('employee/index', [EmployeeController::class, 'index'])->middleware('admin')->name('index');
    Route::get('/employee/create', [EmployeeController::class, 'createEmployee'])->middleware('admin')->name('create');
    Route::post('/employee/save', [EmployeeController::class, 'saveEmployee'])->middleware('admin')->name('save');
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'editEmployee'])->middleware('admin')->name('edit');
    Route::put('/employee/update/{id}', [EmployeeController::class, 'updateEmployee'])->middleware('admin')->name('update');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->middleware('admin')->name('delete');
});

Route::get('/admin/user/list', [UserController::class, 'getUserList'])->name('guest.user.list');
Route::get('/admin/customer/list', [CustomerController::class, 'getUserList'])->middleware('customersp')->name('admin.customer.list');





//Department Routes
Route::name('department.')->group(function () {
    Route::get('/department/index', [DepartmentController::class, 'index'])->middleware('admin')->name('index');
    Route::get('/department/create', [DepartmentController::class, 'create'])->middleware('admin')->name('create');
    Route::post('/department/store', [DepartmentController::class, 'store'])->middleware('admin')->name('store');
    Route::get('/department/edit/{department_id}', [DepartmentController::class, 'edit'])->middleware('admin')->name('edit');
    Route::put('/department/update/{department_id}', [DepartmentController::class, 'update'])->middleware('admin')->name('update');
    Route::delete('/department/delete/{department_id}', [DepartmentController::class, 'destroy'])->middleware('admin')->name('delete');
});

// Request Routes
Route::name('request.')->group(function () {
    Route::get('/request/index', [RequestController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/request/create', [RequestController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/request/store', [RequestController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/request/edit/{request_id}', [RequestController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::put('/request/update/{request_id}', [RequestController::class, 'update'])->middleware('customersp')->name('update');
    Route::delete('/request/delete/{request_id}', [RequestController::class, 'destroy'])->middleware('customersp')->name('delete');
});

//Nhóm thống kê:
// Hiển thị danh sách khách hàng
Route::name('statistical.')->group(function () {
    Route::get('/statistical/index', [StatisticalController::class, 'index'])->middleware('customersp')->name('index');
});
// Route cho API lấy dữ liệu yêu cầu
Route::get('/api/requests', [StatisticalController::class, 'getRequests']);


//Test Nhóm thống kê
// Route cho trang thống kê
Route::get('/admin/statistical', [ReportController::class, 'index'])->name('statistical.static_index');

// Route cho API lấy dữ liệu yêu cầu
Route::get('/api/requests', [ReportController::class, 'getRequests']);





// FAQ Routes
// FAQ Routes
Route::name('faq.')->group(function () {
    Route::get('/faq/index', [FaqController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/faq/create', [FaqController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/faq/store', [FaqController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/faq/edit/{faq_id}', [FaqController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::put('/faq/update/{faq_id}', [FaqController::class, 'update'])->middleware('customersp')->name('update');
    Route::delete('/faq/delete/{faq_id}', [FaqController::class, 'destroy'])->middleware('customersp')->name('delete');

    // Route for unansweredByDate
    Route::get('/faq/unansweredByDate', [FaqController::class, 'unansweredByDate'])->middleware('customersp')->name('unansweredByDate');
});




//Account
Route::get('account', [UserController::class, 'indexAccount'])->name('indexAccount');
