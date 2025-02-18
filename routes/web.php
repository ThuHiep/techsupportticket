<?php

use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\RequestController;
use App\Http\Controllers\admin\FaqController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\StatisticalController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\AttachmentController;
use App\Http\Controllers\admin\ArticlesController;
use App\Http\Controllers\admin\ExportController;
use App\Http\Controllers\admin\RequestTypeController;



use App\Http\Controllers\guest\HomepageController;
use App\Http\Controllers\guest\UserController;
use App\Http\Controllers\guest\LoginController;
use App\Http\Controllers\guest\GuestRequestController;
use App\Http\Controllers\guest\ChatbotController;

use App\Http\Controllers\login\AuthController;
use Illuminate\Support\Facades\Route;
//Cấm đụng cái này
Route::get('/', [HomepageController::class, 'login'])->name('homepage.index');

Route::get('/search', [HomepageController::class, 'search']);

/*Route dashboard cho admin*/
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('customersp')->name('dashboard.index');

/*Route login admin*/
Route::get('loginAdmin', [AuthController::class, 'loginAdmin'])->name('loginAdmin');
Route::post('/loginAdminProcess', [AuthController::class, 'LoginAdminProcess'])->name('loginAdminProcess');
/*Route logout admin*/
Route::get('/logoutAdmin', [AuthController::class, 'LogoutAdmin'])->name('logoutAdmin');

/*Route login*/
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('/loginProcess', [AuthController::class, 'LoginProcess'])->name('loginProcess');

/*Route logout*/
Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
/*Route register cua user*/
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/registerProcess', [AuthController::class, 'registerProcess'])->name('registerProcess');
/*Router xử lý ForgotPass*/
Route::get('/forgotPass', [AuthController::class, 'forgotPass'])->name('forgotPass');
Route::post('/forgotPassProcess', [AuthController::class, 'forgotPassProcess'])->name('forgotPassProcess');

// Thêm route trong web.php
Route::post('/delete-otp/{user_id}', [AuthController::class, 'deleteOtp'])->name('deleteOtp');

Route::get('/verifyOTP/{user_id}', [AuthController::class, 'verifyOTP'])->name('verifyOTP');
Route::post('/resend-otp/{user_id}', [AuthController::class, 'resendOtp'])->name('resendOtp');
Route::post('/verifyOTPProcess/{user_id}', [AuthController::class, 'verifyOTPProcess'])->name('verifyOTPProcess');

Route::get('/changePass/{user_id}', [AuthController::class, 'changePass'])->name('changePass');
Route::put('/updatePass/{user_id}', [AuthController::class, 'updatePass'])->name('updatePass');

/*Route thay đổi mật khẩu từ email*/
Route::get('/changePassEmail/{user_id}', [AuthController::class, 'changePassEmail'])->name('changePassEmail');
/*Route update mật khẩu từ email*/
Route::put('/updatePassEmail/{user_id}', [AuthController::class, 'updatePassEmail'])->name('updatePassEmail');

//
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('checkEmail');

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
    Route::get('/employee/editProfile', [EmployeeController::class, 'editProfile'])->middleware('customersp')->name('editProfile');
    Route::get('/check-username-employee/{username}', [EmployeeController::class, 'checkUsernameEmployee'])->middleware('customersp');
    Route::get('/check-email-employee/{email}', [EmployeeController::class, 'checkEmailEmployee'])->middleware('customersp');
    Route::put('/employee/updateProfile', [EmployeeController::class, 'updateProfile'])->middleware('customersp')->name('updateProfile');
    Route::put('/employee/changePass', [EmployeeController::class, 'changePass'])->middleware('customersp')->name('changePass');
});

//Permission
Route::name('permission.')->group(function () {
    Route::get('permission/index', [PermissionController::class, 'index'])->middleware('admin')->name('index');
    Route::get('/permission/create', [PermissionController::class, 'create'])->middleware('admin')->name('create');
    Route::post('/permission/save', [PermissionController::class, 'save'])->middleware('admin')->name('save');
    Route::get('/permission/edit/{id}', [PermissionController::class, 'editPermission'])->middleware('admin')->name('edit');
    Route::put('/permission/update/{id}', [PermissionController::class, 'updatePermission'])->middleware('admin')->name('update');
    Route::delete('/permission/delete/{id}', [PermissionController::class, 'deletePermission'])->middleware('admin')->name('delete');
});
Route::get('/admin/user/list', [UserController::class, 'getUserList'])->name('guest.user.list');
Route::get('/admin/customer/list', [CustomerController::class, 'getUserList'])->name('admin.customer.list');



//Department Routes
Route::name('department.')->group(function () {
    Route::get('/department/index', [DepartmentController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/department/create', [DepartmentController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/department/store', [DepartmentController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/department/edit/{department_id}', [DepartmentController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::put('/department/update/{department_id}', [DepartmentController::class, 'update'])->middleware('customersp')->name('update');
    Route::delete('/department/delete/{department_id}', [DepartmentController::class, 'destroy'])->middleware('customersp')->name('delete');
});

Route::name('requesttype.')->group(function () {
    Route::get('/requesttype/index', [RequestTypeController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/requesttype/create', [RequestTypeController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/requesttype/store', [RequestTypeController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/requesttype/edit/{request_type_id}', [RequestTypeController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::put('/requesttype/update/{request_type_id}', [RequestTypeController::class, 'update'])->middleware('customersp')->name('update');
    Route::delete('/requesttype/delete/{request_type_id}', [RequestTypeController::class, 'destroy'])->middleware('customersp')->name('delete');
});

// Request Routes
Route::name('request.')->group(function () {
    Route::get('/request/index', [RequestController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/request/create', [RequestController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/request/store', [RequestController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/request/edit/{request_id}', [RequestController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::post('/request/reply/{request_id}', [RequestController::class, 'reply'])->middleware('customersp')->name('reply');
    Route::put('/request/update/{request_id}', [RequestController::class, 'update'])->middleware('customersp')->name('update');
    Route::delete('/request/delete/{request_id}', [RequestController::class, 'destroy'])->middleware('customersp')->name('delete');


    Route::get('/request/pendingByDate', [RequestController::class, 'getPendingRequestsByDate'])->name('pendingByDate');
});

// Route để tải file đính kèm
Route::get('/attachments/download/{id}', [AttachmentController::class, 'download'])->name('attachments.download');

//Nhóm thống kê:
// Hiển thị danh sách khách hàng
//Route::name('statistical.')->group(function () {
//    Route::get('/statistical/index', [ReportController::class, 'index'])->middleware('customersp')->name('index');
//});
Route::name('statistical.')->group(function () {
    Route::get('/statistical/index', [ReportController::class, 'index'])->middleware('customersp')->name('index');
});
Route::get('/api/get-request-data', [DashboardController::class, 'getRequestData']);
Route::get('/api/get-time-data', [DashboardController::class, 'getTimeData']);
Route::get('/api/get-time-data', [DashboardController::class, 'getTimeStatistics']);
Route::get('/api/get-departments', [DashboardController::class, 'getDepartments']);
Route::get('/api/get-request-types', [DashboardController::class, 'getRequestTypes']);
Route::get('/department-report', [DashboardController::class, 'getDepartmentReportData']);
// Route cho API lấy dữ liệu yêu cầu
Route::get('/api/requests', [DashboardController::class, 'getRequests']);

// Route trong Laravel
Route::get('/get-time-statistics', [DashboardController::class, 'getTimeStatistics']);



// Test
Route::get('/statistics', [StatisticalController::class, 'index']);
Route::get('/api/customer-stats', [StatisticalController::class, 'getCustomerStats']);
Route::get('/api/search-customers', [StatisticalController::class, 'searchCustomers']);
Route::get('/api/request-type-stats', [StatisticalController::class, 'getRequestTypeStats']);
Route::get('/api/department-stats', [StatisticalController::class, 'getDepartmentStats']);
Route::get('/api/time-stats', [StatisticalController::class, 'getTimeStats']);




// FAQ Routes
// FAQ Routes
Route::name('faq.')->group(function () {
    Route::get('/faq/index', [FaqController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/faq/create', [FaqController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/faq/stores', [FaqController::class, 'store'])->middleware('customersp')->name('store');
    Route::get('/faq/feedback/{faq_id}', [FaqController::class, 'feedback'])->middleware('customersp')->name('feedback');
    Route::put('/faq/feedbackProcess/{faq_id}', [FaqController::class, 'feedbackProcess'])->middleware('customersp')->name('feedbackProcess');
    Route::delete('/faq/delete/{faq_id}', [FaqController::class, 'destroy'])->middleware('customersp')->name('delete');

    // Route for unansweredByDate
    Route::get('/faq/unansweredByDate', [FaqController::class, 'unansweredByDate'])->name('unansweredByDate');
    Route::get('/faq/answer/{faq_id}', [FaqController::class, 'getAnswer'])->name('faq.answer');

    Route::post('/faq/store', [FaqController::class, 'storeAjax'])->name('faq.storeAjax');
});

// Article Routes
Route::name('articles.')->group(function () {
    Route::get('/articles/index', [ArticlesController::class, 'index'])->middleware('customersp')->name('index');
    Route::get('/articles/edit/{article_id}', [ArticlesController::class, 'edit'])->middleware('customersp')->name('edit');
    Route::put('/articles/update/{article_id}', [ArticlesController::class, 'update'])->middleware('customersp')->name('update');
    Route::get('/articles/create', [ArticlesController::class, 'create'])->middleware('customersp')->name('create');
    Route::post('/articles/store', [ArticlesController::class, 'store'])->middleware('customersp')->name('store');

    Route::delete('/articles/delete/{faq_id}', [ArticlesController::class, 'destroy'])->middleware('customersp')->name('delete');
});


//Account
Route::get('account', [UserController::class, 'indexAccount'])->middleware('customer')->name('indexAccount');
Route::put('guest/changePass', [UserController::class, 'changePass'])->middleware('customer')->name('account.changePass');
Route::put('/updateProfile', [UserController::class, 'updateProfile'])->middleware('customer')->name('customer.updateProfile');
Route::post('/account-switch/{id}', [UserController::class, 'switchAccount'])->middleware('customer')->name('account.switch');
Route::delete('remove-account/{customer_id}', [UserController::class, 'removeAccount'])->middleware('customer')->name('account.remove');
Route::get('/feedback/{request_id}', [UserController::class, 'getFeedbackByRequestId'])->middleware('customer');
Route::post('/reply/{request_id}', [UserController::class, 'reply'])->middleware('customer')->name('customer.reply');

Route::get('/check-username-customer/{username}', [UserController::class, 'checkUsernameCustomer'])->middleware('customer');
Route::get('/check-email-customer/{email}', [UserController::class, 'checkEmailCustomer'])->middleware('customer');
// Route để hiển thị form
Route::get('/pend-request', [HomepageController::class, 'showFormRequest'])->middleware('customer')->name('showFormRequest');

// Route để xử lý lưu yêu cầu
Route::post('/pend-request/store', [GuestRequestController::class, 'store'])->middleware('customer')->name('guest.request.store');
// Route để lấy trạng thái yêu cầu
Route::get('/api/request-status/{requestId}', [GuestRequestController::class, 'getRequestStatus'])
    ->middleware('auth') // Hoặc middleware phù hợp
    ->name('api.requestStatus');


//Test-chat bot
Route::get('/chatbot', [ChatbotController::class, 'index']);
Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);
Route::get('/chatbot/faqs', [ChatbotController::class, 'getFAQs']); // Thêm route này

//Xuất file CSV
Route::get('/export-csv/{type}', [ExportController::class, 'export'])->name('export.csv');
