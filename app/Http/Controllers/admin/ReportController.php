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

        // Chuyển đổi dữ liệu thành định dạng mong muốn
        $response = [];
        foreach ($data as $requestTypeId => $items) {
            // Khởi tạo mảng số liệu theo các trạng thái
            $counts = [
                'processed' => array_fill(0, 31, 0), // Đã xử lý
                'processing' => array_fill(0, 31, 0), // Đang xử lý
                'pending' => array_fill(0, 31, 0), // Chưa xử lý
                'cancelled' => array_fill(0, 31, 0)  // Đã hủy
            ];

            foreach ($items as $item) {
                $statusKey = $item->status;  // Trạng thái yêu cầu
                if (array_key_exists($statusKey, $counts)) {
                    $counts[$statusKey][$item->day - 1] = $item->count;
                }
            }

            // Tìm kiếm tên loại yêu cầu
            $requestType = $requestTypes->firstWhere('id', $requestTypeId);
            if ($requestType) {
                $response[] = [
                    'request_type_name' => $requestType->request_type_name,
                    'counts' => $counts
                ];
            }
        }

        // Lấy danh sách khách hàng
        $activeCustomers = Customer::where('status', 'active')
            ->withCount('requests') // Đếm số lượng yêu cầu
            ->get(['customer_id', 'full_name']);

        // Màu sắc của các khách hàng
        $customerColors = ['#3498db', '#1abc9c', '#9b59b6', '#e74c3c', '#f1c40f'];

        // Tạo màu sắc cho phòng ban
        $departmentColors = [];
        foreach ($departments as $index => $department) {
            $departmentColors[$department->department_name] = $customerColors[$index % count($customerColors)];
        }

        // Initialize an array to hold department data
        $departmentData = [];

        // Loop through each department to gather request statistics
        foreach ($departments as $department) {
            $departmentData[$department->department_name] = [
                'Đang xử lý' => DB::table('request')
                    ->where('department_id', $department->department_id) // Correct foreign key
                    ->where('status', 'Đang xử lý')
                    ->count(),
                'Chưa xử lý' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Chưa xử lý')
                    ->count(),
                'Hoàn thành' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Hoàn thành')
                    ->count(),
                'Đã hủy' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Đã hủy')
                    ->count(),
            ];
        }

        // Trả về view với dữ liệu đã xử lý
        return view('admin.dashboard.layout', compact('response', 'template', 'requestTypes', 'logged_user', 'activeCustomers', 'customerColors', 'departments', 'departmentColors', 'departmentData'));
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
        $data = [];

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

//    public function getTimeData(Request $request)
//    {
//        // Logic to retrieve the data from your database
//        // For example, you might want to group requests by month
//        $data = DB::table('request')
//            ->select(DB::raw('MONTH(create_at) as month, COUNT(*) as count'))
//            ->whereYear('create_at', date('Y')) // Get data for the current year
//            ->groupBy('month')
//            ->orderBy('month')
//            ->get();
//
//        // Format the data into an associative array
//        $formattedData = [];
//        foreach ($data as $item) {
//            $monthName = date('F', mktime(0, 0, 0, $item->month, 1)); // Convert month number to name
//            $formattedData[$monthName] = $item->count;
//        }
//
//        return response()->json($formattedData);
//    }
    public function getTimeData(Request $request)
    {
        $query = DB::table('request')
            ->join('department', 'request.department_id', '=', 'department.department_id')
            ->join('request_type', 'request.request_type_id', '=', 'request_type.request_type_id');

        // Apply filters
        if ($department = $request->input('department')) {
            $query->where('request.department_id', $department);
        }
        if ($status = $request->input('status')) {
            $statusMap = [
                'pending' => 'Chưa xử lý',
                'in_progress' => 'Đang xử lý',
                'completed' => 'Hoàn thành',
                'canceled' => 'Đã hủy'
            ];

            if (array_key_exists($status, $statusMap)) {
                $query->where('request.status', $statusMap[$status]);
            }
        }
        if ($requestType = $request->input('type')) {
            $query->where('request.request_type_id', $requestType);
        }

        // Logic to group by month for the current year
        $data = $query->select(DB::raw('MONTH(request.create_at) as month, COUNT(*) as count'))
            ->whereYear('request.create_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Format the data into an associative array
        $formattedData = [];
        foreach ($data as $item) {
            $monthName = date('F', mktime(0, 0, 0, $item->month, 1));
            $formattedData[$monthName] = $item->count;
        }

        //dd($formattedData); // Để kiểm tra dữ liệu
        return response()->json($formattedData);


    }

    // Controller method to get departments
    public function getDepartments(Request $request)
    {
        // Lấy tất cả các phòng ban với thông tin yêu cầu và trạng thái của yêu cầu
        $query = DB::table('department')
            ->join('request', 'department.department_id', '=', 'request.department_id')
            ->select('department.department_id', 'department.department_name', 'request.status')
            ->distinct();

        // Kiểm tra nếu có yêu cầu lọc theo trạng thái
        if ($status = $request->input('status')) {
            $statusMap = [
                'pending' => 'Chưa xử lý',
                'in_progress' => 'Đang xử lý',
                'completed' => 'Hoàn thành',
                'canceled' => 'Đã hủy'
            ];

            // Kiểm tra trạng thái hợp lệ và lọc theo trạng thái
            if (array_key_exists($status, $statusMap)) {
                $query->where('request.status', $statusMap[$status]);
            }
        }

        // Lấy các phòng ban đã được lọc
        $departments = $query->get();

        // Trả về dữ liệu phòng ban dưới dạng JSON
        return response()->json($departments);
    }

    // Ví dụ API trả về danh sách yêu cầu của từng phòng ban
        public function getDepartmentReportData(Request $request)
        {
            // Fetch department data along with the count of requests per status
            $departmentData = DB::table('department')
                ->leftJoin('request', 'department.department_id', '=', 'request.department_id')
                ->select('department.department_name', 'request.status', DB::raw('COUNT(request.request_id) as request_count'))
                ->groupBy('department.department_id', 'department.department_name', 'request.status')
                ->get();

            // Log the raw data to check
            \Log::info($departmentData);

            // Create a mapping for statuses
            $statusMapping = [
                'In Progress' => 'Đang xử lý',
                'Not Processed' => 'Chưa xử lý',
                'Completed' => 'Hoàn thành',
                'Canceled' => 'Đã hủy',
                // Add any other statuses that may exist
            ];

            // Restructure data for easier frontend processing
            $formattedData = [];
            foreach ($departmentData as $data) {
                $department = $data->department_name;

                // Map the status to Vietnamese
                $status = $statusMapping[$data->status] ?? 'Chưa xử lý'; // Default to 'Chưa xử lý' if not found

                $requestCount = $data->request_count;

                // Initialize department data if not already set
                if (!isset($formattedData[$department])) {
                    $formattedData[$department] = [
                        'Đang xử lý' => 0,
                        'Chưa xử lý' => 0,
                        'Hoàn thành' => 0,
                        'Đã hủy' => 0
                    ];
                }

                // Accumulate counts for the appropriate status
                $formattedData[$department][$status] += $requestCount; // Use += to accumulate counts
            }

            return response()->json($formattedData);
        }




// Controller method to get request types
    public function getRequestTypes()
    {
        $requestTypes = DB::table('request_type')->select('request_type_id', 'request_type_name')->get();
        return response()->json($requestTypes);
    }

}
