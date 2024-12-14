<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer; // Import Model Customer
use App\Models\Employee;
use App\Models\Request;
use App\Models\Request as SupportRequest; // Import Model Request
use App\Models\User; // Import Model User
use App\Models\FAQ; // Import Model User
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Constructor logic
    }

    public function index()
    {
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $config = $this->config();
        // Số bài viết chưa phản hồi hôm nay
        $unansweredFaqsToday = FAQ::where('status', 'Chưa phản hồi')
            ->whereDate('create_at', now()->toDateString())
            ->count();





        // Số khách hàng hôm nay
        $totalCustomersToday = Customer::whereDate('create_at', now()->toDateString())->count();

        // Số khách hàng ngày hôm qua
        $totalCustomersYesterday = Customer::whereDate('create_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi khách hàng so với hôm qua
        $customerPercentageChange = $this->calculatePercentageChange($totalCustomersToday, $totalCustomersYesterday);

        // Tổng số yêu cầu từ bảng request hôm nay
        $totalRequestsToday = SupportRequest::whereDate('create_at', now()->toDateString())->count();

        // Tổng số yêu cầu từ bảng request ngày hôm qua
        $totalRequestsYesterday = SupportRequest::whereDate('create_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi yêu cầu so với hôm qua
        $requestPercentageChange = $this->calculatePercentageChange($totalRequestsToday, $totalRequestsYesterday);

        // Tổng số người dùng từ bảng user hôm nay
        $totalUsersToday = User::whereDate('create_at', now()->toDateString())->count();

        // Tổng số người dùng từ bảng user ngày hôm qua
        $totalUsersYesterday = User::whereDate('create_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi người dùng so với hôm qua
        $userPercentageChange = $this->calculatePercentageChange($totalUsersToday, $totalUsersYesterday);

        // Tổng số bài viết từ bảng faq hôm nay
        $totalFaqsToday = FAQ::whereDate('create_at', now()->toDateString())->count();

        // Tổng số bài viết từ bảng faq ngày hôm qua
        $totalFaqsYesterday = FAQ::whereDate('create_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi bài viết so với hôm qua
        $faqPercentageChange = $this->calculatePercentageChange($totalFaqsToday, $totalFaqsYesterday);


        // Dữ liệu yêu cầu theo trạng thái
        $requestStatusCounts = [
            'processing' => Request::where('status', 'Chưa xử lý')->count(),
            'handled' => Request::where('status', 'Đang xử lý')->count(),
            'completed' => Request::where('status', 'Hoàn thành')->count(),
            'cancelled' => Request::where('status', 'Đã hủy')->count(),
        ];

        // Lấy dữ liệu yêu cầu trong tuần này từ Thứ Hai đến Chủ Nhật
        $requestsThisWeek = SupportRequest::selectRaw('WEEKDAY(create_at) as weekday, COUNT(*) as total')
            ->whereBetween('create_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('weekday')
            ->orderBy('weekday', 'asc')
            ->get();

        // Tạo mảng mặc định với số lượng yêu cầu là 0 cho cả tuần từ Thứ Hai đến Chủ Nhật
        $weekdays = ['Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy', 'CN'];
        $requestData = array_fill(0, 7, ['day' => '', 'total' => 0]);

        foreach ($weekdays as $index => $day) {
            $requestData[$index]['day'] = $day;
        }

        // Cập nhật dữ liệu thực tế từ $requestsThisWeek
        foreach ($requestsThisWeek as $request) {
            $requestData[$request->weekday]['total'] = $request->total;
        }

        $template = 'admin.dashboard.home.index';

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'config',
            'unansweredFaqsToday',
            'totalCustomersToday',
            'customerPercentageChange',
            'totalRequestsToday',
            'requestPercentageChange',
            'totalUsersToday',
            'userPercentageChange',
            'totalFaqsToday',
            'faqPercentageChange',
            'requestStatusCounts',
            'requestData',

        ));
    }

    // Hàm tính phần trăm thay đổi
    private function calculatePercentageChange($todayCount, $yesterdayCount)
    {
        if ($yesterdayCount == 0 && $todayCount == 0) {
            return 0; // Không thay đổi nếu cả hôm qua và hôm nay đều không có
        }

        if ($yesterdayCount == 0) {
            return $todayCount > 0 ? '100%' : 0; // Nếu hôm qua không có, nhưng hôm nay có
        }

        return round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 2);
    }
    private function config()
    {
        return [
            'js' => [

                'admin/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                'admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                'admin/js/plugins/easypiechart/jquery.easypiechart.js',
                'admin/js/plugins/sparkline/jquery.sparkline.min.js',
                'admin/js/demo/sparkline-demo.js',
            ],
        ];
    }
}
