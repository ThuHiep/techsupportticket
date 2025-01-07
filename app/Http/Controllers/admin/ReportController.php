<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\RequestType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $template = 'admin.statistical.index';
        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

        // Lấy loại yêu cầu từ cơ sở dữ liệu với số lượng yêu cầu
        $requestTypes = RequestType::withCount('requests')->get();

        // Lấy phòng ban từ cơ sở dữ liệu
        $departments = Department::withCount('requests')->get();

        // Truy vấn để lấy số liệu yêu cầu theo ngày và trạng thái
        $query = DB::table('request')
            ->select(DB::raw('DAY(create_at) as day'), 'request_type_id', 'status', DB::raw('COUNT(request_id) as count'))
            ->groupBy(DB::raw('DAY(create_at), request_type_id, status'));

        $data = $query->get()->groupBy('request_type_id');

        // Initialize an array to hold request type data
        $requestTypeData = [];

        foreach ($requestTypes as $requestType) {
            $requestCounts = DB::table('request')
                ->select('status', DB::raw('count(*) as count'))
                ->where('request_type_id', $requestType->request_type_id)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $requestTypeData[$requestType->request_type_name] = [
                'Đang xử lý' => $requestCounts['Đang xử lý'] ?? 0,
                'Chưa xử lý' => $requestCounts['Chưa xử lý'] ?? 0,
                'Hoàn thành' => $requestCounts['Hoàn thành'] ?? 0,
                'Đã hủy' => $requestCounts['Đã hủy'] ?? 0,
            ];
        }
        //dd($requestTypeData);
        // Lấy danh sách khách hàng
        $activeCustomers = Customer::where('status', 'active')
            ->withCount('requests') // Đếm số lượng yêu cầu
            ->get(['customer_id', 'full_name']);

        function generateColors($numColors) {
            $colors = [];
            for ($i = 0; $i < $numColors; $i++) {
                $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            }
            return $colors;
        }
        // Màu sắc của các khách hàng
        $customerColors = generateColors(5000); // Thay đổi số lượng theo nhu cầu

        // Tạo màu sắc cho phòng ban
        $departmentColors = [];
        foreach ($departments as $index => $department) {
            $departmentColors[$department->department_name] = $customerColors[$index % count($customerColors)];
        }

        // Initialize an array to hold department data
        $departmentData = [];

        // Loop through each department to gather request statistics
        foreach ($departments as $department) {

            $requestCounts = DB::table('request')
                ->select('status', DB::raw('count(*) as count'))
                ->where('department_id', $department->department_id)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $departmentData[$department->department_name] = [
                'Đang xử lý' => $requestCounts['Đang xử lý'] ?? 0,
                'Chưa xử lý' => $requestCounts['Chưa xử lý'] ?? 0,
                'Hoàn thành' => $requestCounts['Hoàn thành'] ?? 0,
                'Đã hủy' => $requestCounts['Đã hủy'] ?? 0,
            ];
        }
        //dd($departmentData);

        // Time-based statistics
        $timeData = $this->getTimeBasedStatistics();
        //dd($timeData);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        // Trả về view với dữ liệu đã xử lý
        return view('admin.dashboard.layout', compact(
            'data',
            'template',
            'requestTypes',
            'logged_user',
            'activeCustomers',
            'customerColors',
            'departments',
            'departmentColors',
            'departmentData',
            'requestTypeData', // Gửi dữ liệu trạng thái theo loại yêu cầu
            'timeData',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function getRequests(Request $request)
    {
        $query = DB::table('request')
            ->select(DB::raw('DAY(create_at) as day'), 'request_type_name', DB::raw('COUNT(request_id) as count'))
            ->groupBy(DB::raw('DAY(create_at), request_type_name'));

        $data = $query->get()->groupBy('request_type_name');

        // Chuyển đổi dữ liệu thành định dạng mong muốn
        $response = [];
        foreach ($data as $requestType => $items) {
            $counts = array_fill(0, 31, 0);
            foreach ($items as $item) {
                $counts[$item->day - 1] = $item->count;
            }
            $response[] = [
                'request_type_name' => $requestType,
                'counts' => $counts
            ];
        }

        return response()->json($response);
    }

    public function getRequestData(Request $request)
    {
        $period = $request->input('period');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $data = DB::table('request')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Kiểm tra xem startDate và endDate có được gửi hay không
        if ($startDate && $endDate) {
            // Nếu có, chuyển về định dạng Carbon để dễ dàng so sánh
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            // Lấy dữ liệu cho khoảng thời gian từ startDate đến endDate
            while ($startDate <= $endDate) {
                $date = $startDate->format('Y-m-d');
                $data[$date] = $this->getRequestsCountByDate($date);
                $startDate->addDay(); // Di chuyển tới ngày tiếp theo
            }
        } else {
            // Nếu không có startDate và endDate, xử lý theo các khoảng thời gian 'today', 'monthly', 'yearly'
            switch ($period) {
                case 'today':
                    // Lấy dữ liệu cho 7 ngày qua
                    for ($i = 6; $i >= 0; $i--) {
                        $date = Carbon::today()->subDays($i)->format('Y-m-d');
                        $data[$date] = $this->getRequestsCountByDate($date);
                    }
                    break;

                case 'monthly':
                    // Lấy dữ liệu cho tháng hiện tại
                    $month = Carbon::now()->format('Y-m');
                    $data = $this->getRequestsCountByMonth($month);
                    break;

                case 'yearly':
                    // Lấy dữ liệu cho 10 năm qua
                    for ($i = 0; $i < 10; $i++) {
                        $year = Carbon::now()->subYears($i)->format('Y');
                        $data[$year] = $this->getRequestsCountByYear($year);
                    }
                    break;
            }
        }
        return response()->json($data);
    }


    private function getRequestsCountByDate($date)
    {
        // Lấy số lượng yêu cầu từ bảng request cho một ngày cụ thể
        return DB::table('request')
            ->whereDate('create_at', $date) // Điều kiện lọc theo ngày
            ->count(); // Đếm số lượng bản ghi
    }

    private function getRequestsCountByMonth($month)
    {
        // Phân tích tháng và năm từ chuỗi $month
        $date = Carbon::createFromFormat('Y-m', $month);
        $year = $date->year;
        $month = $date->month;

        // Lấy số ngày trong tháng
        $daysInMonth = $date->daysInMonth;

        // Lấy số lượng yêu cầu theo từng ngày trong tháng
        $results = DB::table('request')
            ->select(DB::raw('DAY(create_at) as day'), DB::raw('COUNT(request_id) as count'))
            ->whereYear('create_at', $year)
            ->whereMonth('create_at', $month)
            ->groupBy(DB::raw('DAY(create_at)'))
            ->get();

        // Tạo mảng mặc định cho tất cả các ngày trong tháng (giá trị mặc định là 0)
        $data = array_fill(1, $daysInMonth, 0);

        // Gán dữ liệu đếm yêu cầu vào mảng
        foreach ($results as $result) {
            $data[$result->day] = $result->count;
        }

        return $data;
    }

    private function getRequestsCountByYear($year)
    {
        // Lấy số lượng yêu cầu từ bảng request cho năm cụ thể
        return DB::table('request')
            ->whereYear('create_at', $year) // Lọc theo năm
            ->count(); // Đếm số lượng bản ghi
    }


    // Controller method to get departments
    public function getDepartments(Request $request)
    {
        // Lấy tất cả các phòng ban
        $departments = DB::table('department')->get();

        $departmentData = [];

        // Duyệt qua từng phòng ban để thu thập thống kê yêu cầu
        foreach ($departments as $department) {
            $requestCounts = DB::table('request')
                ->select('status', DB::raw('count(*) as count'))
                ->where('department_id', $department->department_id)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Kiểm tra kết quả truy vấn
            //dd($requestCounts);

            $departmentData[] = [
                'department_id' => $department->department_id,
                'department_name' => $department->department_name,
                'status' => [
                    'Đang xử lý' => $requestCounts['Đang xử lý'] ?? 0,
                    'Chưa xử lý' => $requestCounts['Chưa xử lý'] ?? 0,
                    'Hoàn thành' => $requestCounts['Hoàn thành'] ?? 0,
                    'Đã hủy' => $requestCounts['Đã hủy'] ?? 0,
                ],
            ];
        }

        // Trả về dữ liệu phòng ban kèm thống kê dưới dạng JSON
        return response()->json($departmentData);
    }

    // Controller method to get request types
    public function getRequestTypes()
    {
        $requestTypes = DB::table('request_type')->select('request_type_id', 'request_type_name')->get();
        return response()->json($requestTypes);
    }


    protected function getTimeBasedStatistics()
    {
        $timeData = [];
        $timeData['Ngày'] = $this->getDailyStatistics();
        $timeData['Tuần'] = $this->getWeeklyStatistics();
        $timeData['Tháng'] = $this->getMonthlyStatistics();
        $timeData['Năm'] = $this->getYearlyStatistics();
        return $timeData;
    }

//    private function getDailyStatistics()
//    {
//        $startDate = now()->startOfMonth();
//        $endDate = now()->endOfMonth();
//        $days = [];
//
//        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
//            $days[$date->format('Y-m-d')] = [
//                'Đang xử lý' => 0,
//                'Chưa xử lý' => 0,
//                'Hoàn thành' => 0,
//                'Đã hủy' => 0,
//            ];
//        }
//
//        $dailyStats = DB::table('request')
//            ->select(DB::raw("DATE_FORMAT(create_at, '%Y-%m-%d') as period"), 'status', DB::raw('count(*) as total'))
//            ->whereBetween('create_at', [$startDate, $endDate])
//            ->groupBy('period', 'status')
//            ->get();
//
//        // Kiểm tra kết quả của dailyStats
//        \Log::info('Daily Stats:', $dailyStats->toArray());
//
//        foreach ($dailyStats as $stat) {
//            $days[$stat->period][$stat->status] = $stat->total;
//        }
//
//        return array_map(function ($totals, $period) {
//            return ['period' => $period, 'total' => $totals];
//        }, $days, array_keys($days));
//    }
    private function getDailyStatistics()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        $days = [];

        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $days[$date->format('Y-m-d')] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $dailyStats = DB::table('request')
            ->select(DB::raw("DATE_FORMAT(create_at, '%Y-%m-%d') as period"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('period', 'status')
            ->get();

        foreach ($dailyStats as $stat) {
            $days[$stat->period][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $period) {
            return [
                'period' => $period,
                'total' => $totals
            ];
        }, $days, array_keys($days));
    }

    private function getWeeklyStatistics()
    {
        $weeks = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        // Tạo mảng tuần
        for ($date = clone $startDate; $date <= $endDate; $date->addWeek()) {
            $weeks[$date->format('Y-W')] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        // Lấy thống kê tuần
        $weeklyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year, WEEK(create_at, 1) as week, status, count(*) as total"))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('year', 'week', 'status')
            ->get();

        // Cập nhật mảng tuần với số liệu
        foreach ($weeklyStats as $stat) {
            $weekKey = $stat->year . '-' . str_pad($stat->week, 2, '0', STR_PAD_LEFT);
            if (isset($weeks[$weekKey])) {
                $weeks[$weekKey][$stat->status] = $stat->total;
            }
        }

        // Trả về dữ liệu theo định dạng mong muốn
        return array_map(function ($totals, $period) {
            return [
                'period' => $period,
                'totals' => $totals,
            ];
        }, $weeks, array_keys($weeks));
    }

    private function getMonthlyStatistics()
    {
        $months = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $monthlyStats = DB::table('request')
            ->select(DB::raw("MONTH(create_at) as month"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('month', 'status')
            ->get();

        foreach ($monthlyStats as $stat) {
            $months[$stat->month][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $month) {
            return ['period' => $month, 'total' => $totals];
        }, $months, array_keys($months));
    }

    private function getYearlyStatistics()
    {
        $years = [];

        for ($year = 2020; $year <= 2030; $year++) {
            $years[$year] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $yearlyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', ['2020-01-01', '2030-12-31'])
            ->groupBy('year', 'status')
            ->get();

        foreach ($yearlyStats as $stat) {
            $years[$stat->year][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $year) {
            return ['period' => $year, 'total' => $totals];
        }, $years, array_keys($years));
    }
}
