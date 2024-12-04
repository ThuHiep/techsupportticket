<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer; // Import Model Customer
use App\Models\Request as SupportRequest; // Import Model Request
use App\Models\User; // Import Model User


class DashboardController extends Controller
{
    public function __construct()
    {
        // Constructor logic
    }

    public function index()
    {
        $config = $this->config();
    
        // Số khách hàng hôm nay
        $totalCustomersToday = Customer::whereDate('create_at', now()->toDateString())->count();
    
        // Số khách hàng ngày hôm qua
        $totalCustomersYesterday = Customer::whereDate('create_at', now()->subDay()->toDateString())->count();
    
        // Tính phần trăm thay đổi khách hàng so với hôm qua
        $customerPercentageChange = $this->calculatePercentageChange($totalCustomersToday, $totalCustomersYesterday);

        // Tổng số yêu cầu từ bảng request hôm nay
        $totalRequestsToday = SupportRequest::whereDate('received_at', now()->toDateString())->count();
        
        // Tổng số yêu cầu từ bảng request ngày hôm qua
        $totalRequestsYesterday = SupportRequest::whereDate('received_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi yêu cầu so với hôm qua
        $requestPercentageChange = $this->calculatePercentageChange($totalRequestsToday, $totalRequestsYesterday);
        
        // Tổng số người dùng từ bảng user hôm nay
        $totalUsersToday = User::whereDate('create_at', now()->toDateString())->count();
        
        // Tổng số người dùng từ bảng user ngày hôm qua
        $totalUsersYesterday = User::whereDate('create_at', now()->subDay()->toDateString())->count();

        // Tính phần trăm thay đổi người dùng so với hôm qua
        $userPercentageChange = $this->calculatePercentageChange($totalUsersToday, $totalUsersYesterday);

        // // Tổng số bài viết từ bảng faq hôm nay
        // $totalFaqsToday = Faq::whereDate('created_at', now()->toDateString())->count();
        
        // // Tổng số bài viết từ bảng faq ngày hôm qua
        // $totalFaqsYesterday = Faq::whereDate('created_at', now()->subDay()->toDateString())->count();

        // // Tính phần trăm thay đổi bài viết so với hôm qua
        // $faqPercentageChange = $this->calculatePercentageChange($totalFaqsToday, $totalFaqsYesterday);
    
        
        $template = 'backend.dashboard.home.index';
    
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'totalCustomersToday',
            'customerPercentageChange',
            'totalRequestsToday',
            'requestPercentageChange',
            'totalUsersToday',
            'userPercentageChange',
        ));
    }

    // Hàm tính phần trăm thay đổi
    private function calculatePercentageChange($todayCount, $yesterdayCount)
    {
        if ($yesterdayCount == 0) {
            return $todayCount > 0 ? '100%+' : '0%';
        }
        
        return number_format((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 2) . '%';
    }
    
    private function config()
    {
        return [
            'js' => [
                'backend/js/plugins/flot/jquery.flot.js',
                'backend/js/plugins/flot/jquery.flot.tooltip.min.js',
                'backend/js/plugins/flot/jquery.flot.spline.js',
                'backend/js/plugins/flot/jquery.flot.resize.js',
                'backend/js/plugins/flot/jquery.flot.pie.js',
                'backend/js/plugins/flot/jquery.flot.symbol.js',
                'backend/js/plugins/flot/jquery.flot.time.js',
                'backend/js/plugins/peity/jquery.peity.min.js',
                'backend/js/demo/peity-demo.js',
                'backend/js/inspinia.js',
                'backend/js/plugins/pace/pace.min.js',
                'backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                'backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                'backend/js/plugins/easypiechart/jquery.easypiechart.js',
                'backend/js/plugins/sparkline/jquery.sparkline.min.js',
                'backend/js/demo/sparkline-demo.js',
            ],
        ];
    }
}
