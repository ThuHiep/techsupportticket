<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;  // Thêm dòng này để sử dụng DB\
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.statistical.index';
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $month = $request->input('month', 'all');
        $year = $request->input('year', 'all');

        $query = DB::table('request_type')
            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
            ->where('request_type.status', 'active');

        // Lọc theo ngày bắt đầu và kết thúc
        if ($startDate && $endDate) {
            $query->whereBetween('request.created_at', [$startDate, $endDate]);
        }

        // Truy vấn khách hàng và đếm số yêu cầu hỗ trợ
        // Đếm số lượng khách hàng có status là active
        $activeCustomers = Customer::where('status', 'active')->withCount('requests')->get(['customer_id', 'full_name']);
        $customerColors = ['#3498db', '#1abc9c', '#9b59b6', '#e74c3c', '#f1c40f'];

        // Lấy dữ liệu sau khi lọc
        $requestTypes = $query->groupBy('request_type.request_type_name')->get();

        return view('admin.dashboard.layout', compact('template', 'activeCustomers', 'requestTypes', 'startDate', 'endDate', 'month', 'year','customerColors'));
    }
}
