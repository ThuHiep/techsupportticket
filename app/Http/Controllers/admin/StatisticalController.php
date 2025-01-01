<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Request; // Alias your model to avoid conflict
use App\Models\RequestType;
use Illuminate\Http\Request as HttpRequest; // Đổi tên cho rõ ràng
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function index(HttpRequest $request)
    {
        // Lấy dữ liệu theo yêu cầu
        $customerStats = $this->getCustomerStatistics($request);
        $requestTypeStats = $this->getRequestTypeStatistics($request);
        $departmentStats = $this->getDepartmentStatistics($request);
        $timeStats = $this->getTimeStatistics($request);
        $statuses = $this->getCustomerReportData(); // Gọi phương thức này
        //dd($customerStats);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.statistical.static_index', compact(
            'customerStats',
            'requestTypeStats',
            'departmentStats',
            'timeStats',
            'statuses',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    private function getCustomerStatistics(HttpRequest $request)
    {
        return Request::with('customer')
            ->selectRaw('customer_id, status, COUNT(*) as request_count')
            ->groupBy('customer_id', 'status')
            ->get()
            ->map(function ($item) {
                return [
                    'full_name' => $item->customer ? $item->customer->full_name : 'Không có tên',
                    'status' => $item->status,
                    'request_count' => $item->request_count,
                ];
            });
    }
    //    public function searchCustomers(Request $request)
    //    {
    //        $name = $request->input('name');
    //        $customers = Customer::where('full_name', 'LIKE', "%$name%")->get(); // Giả sử bạn có model Customer
    //
    //        return response()->json($customers);
    //    }

    public function getCustomerReportData()
    {
        return Request::select('status')->distinct()->pluck('status');
    }

    private function getRequestTypeStatistics(HttpRequest $request)
    {
        return Request::with('requestType')
            ->selectRaw('request_type_id, COUNT(*) as request_count')
            ->groupBy('request_type_id')
            ->get()
            ->map(function ($item) {
                return [
                    'request_type_name' => $item->requestType->request_type_name,
                    'request_count' => $item->request_count,
                ];
            });
    }

    private function getDepartmentStatistics(HttpRequest $request)
    {
        return Request::with('department')
            ->selectRaw('department_id, COUNT(*) as request_count')
            ->groupBy('department_id')
            ->get()
            ->map(function ($item) {
                return [
                    'department_name' => $item->department->department_name,
                    'request_count' => $item->request_count,
                ];
            });
    }

    private function getTimeStatistics(HttpRequest $request)
    {
        return Request::selectRaw('DATE(create_at) as request_date, COUNT(*) as request_count') // Sửa tên cột
            ->groupBy('request_date')
            ->get();
    }

    public function getCustomerStats(HttpRequest $request)
    {
        $customerStats = $this->getCustomerStatistics($request);
        return response()->json($customerStats);
    }

    public function getRequestTypeStats(HttpRequest $request)
    {
        $requestTypeStats = $this->getRequestTypeStatistics($request);
        return response()->json($requestTypeStats);
    }

    public function getDepartmentStats(HttpRequest $request)
    {
        $departmentStats = $this->getDepartmentStatistics($request);
        return response()->json($departmentStats);
    }

    public function getTimeStats(HttpRequest $request)
    {
        $timeStats = $this->getTimeStatistics($request);
        return response()->json($timeStats);
    }
}
