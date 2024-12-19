<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{
//    public function index(Request $request)
//    {
//        $template = 'admin.statistical.index';
//        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();
//
//        // Khởi tạo truy vấn
//        $requestTypes = DB::table('request_type')
//            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
//            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
//            ->where('request_type.status', 'active')
//            ->groupBy('request_type.request_type_name')
//            ->get();
//
//        // Nếu không có dữ liệu, trả về thông báo
//        if ($requestTypes->isEmpty()) {
//            $requestTypes = collect([(object) ['request_type_name' => 'Không có dữ liệu', 'count' => 0]]);
//        }
//
//        // Lấy danh sách khách hàng
//        $activeCustomers = Customer::where('status', 'active')
//            ->withCount('requests') // Đếm số lượng yêu cầu
//            ->get(['customer_id', 'full_name']);
//
//        $customerColors = ['#3498db', '#1abc9c', '#9b59b6', '#e74c3c', '#f1c40f'];
//
//        return view('admin.dashboard.layout', compact('template', 'activeCustomers', 'requestTypes', 'logged_user', 'customerColors'));
//    }
//
//    public function getCustomerRequests()
//    {
//        $customers = Customer::withCount('request')
//            ->where('status', 'active')
//            ->get(['customer_id', 'full_name']);
//
//        return response()->json($customers);
//    }
//
//    public function getRequests(Request $request)
//    {
//        $type = $request->input('type', 'all');
//        $month = $request->input('month', 'all');
//
//        $query = DB::table('requests')
//            ->select(DB::raw('DAY(create_at) as day'), 'request_type_name', DB::raw('COUNT(request_id) as count'))
//            ->groupBy(DB::raw('DAY(create_at), request_type_name'));
//
//        if ($type !== 'all') {
//            $query->where('request_type_name', $type);
//        }
//
//        if ($month !== 'all') {
//            $query->whereMonth('create_at', $month);
//        }
//
//        $data = $query->get()->groupBy('request_type_name');
//
//        // Chuyển đổi dữ liệu thành định dạng mong muốn
//        $response = [];
//        foreach ($data as $requestType => $items) {
//            $days = [];
//            $counts = array_fill(0, 31, 0); // Giả định 31 ngày trong tháng
//            foreach ($items as $item) {
//                $days[] = $item->day;
//                $counts[$item->day - 1] = $item->count; // Đếm số yêu cầu theo ngày
//            }
//            $response[] = [
//                'request_type_name' => $requestType,
//                'day' => $days,
//                'counts' => $counts
//            ];
//        }
//
//        return response()->json($response);
//    }
}
