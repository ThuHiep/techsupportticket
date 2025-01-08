<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\RequestType;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Http\Request;
use App\Models\Request as HttpRequest;




class ExportController extends Controller
{
    public function export($type, Request $request)
    {

        $directoryPath = public_path('admin/csv');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Tạo tên file với chuỗi ngẫu nhiên 8 số
        do {
            $randomSuffix = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $filePath = $directoryPath . '/report_' . $type . '_' . $randomSuffix . '.csv';
        } while (file_exists($filePath)); // Kiểm tra xem file đã tồn tại chưa

        $file = fopen($filePath, 'w');

        // Ghi BOM để Excel nhận diện mã hóa UTF-8
        fwrite($file, "\xEF\xBB\xBF");

        // Xử lý xuất CSV theo loại báo cáo
        switch ($type) {
            case 'customer':
                fputcsv($file, ['Mã khách hàng', 'Họ và tên', 'Số lượng yêu cầu']); // Ghi tiêu đề
                // Lấy tên hoặc mã khách hàng từ request
                $customerName = $request->input('customer_name', '');
                $customerId = $request->input('customer_id', '');
                // Lấy danh sách khách hàng và số lượng yêu cầu của họ, có lọc
                $query = Customer::withCount('requests');

                // Lọc theo tên khách hàng nếu có
                if (!empty($customerName)) {
                    $query->where('full_name', 'LIKE', '%' . $customerName . '%');
                }

                // Lọc theo mã khách hàng nếu có
                if (!empty($customerId)) {
                    $query->where('customer_id', $customerId);
                }
                $customers = $query->get();
                // Khởi tạo biến tổng cộng
                $totalRequests = 0;
                foreach ($customers as $customer) {
                    fputcsv($file, [
                        $customer->customer_id,
                        $this->formatVietnameseName($customer->full_name),
                        $customer->requests_count
                    ]); // Ghi dữ liệu

                    // Cộng dồn số lượng yêu cầu
                    $totalRequests += $customer->requests_count;
                }
                // Ghi dòng tổng cộng
                fputcsv($file, ['Tổng cộng', '', $totalRequests]);
                break;
            case 'requestType':
                // Ghi tiêu đề cho các cột
                fputcsv($file, ['Request Type', 'Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);

                // Lấy loại yêu cầu từ request
                $selectedRequestType = $request->input('type', 'all'); // Mặc định là 'all'

                // Lấy danh sách các loại yêu cầu
                if ($selectedRequestType !== 'all') {
                    $requestTypes = RequestType::where('request_type_name', $selectedRequestType)->withCount('requests')->get();
                } else {
                    $requestTypes = RequestType::withCount('requests')->get();
                }

                $totalPending = 0;
                $totalInProgress = 0;
                $totalCompleted = 0;
                $totalCanceled = 0;

                foreach ($requestTypes as $requestType) {
                    // Đếm số yêu cầu theo từng trạng thái
                    $pendingCount = $requestType->requests()->where('status', 'Chưa xử lý')->count();
                    $inProgressCount = $requestType->requests()->where('status', 'Đang xử lý')->count();
                    $completedCount = $requestType->requests()->where('status', 'Hoàn thành')->count();
                    $canceledCount = $requestType->requests()->where('status', 'Đã hủy')->count();

                    // Cộng dồn số lượng vào tổng
                    $totalPending += $pendingCount;
                    $totalInProgress += $inProgressCount;
                    $totalCompleted += $completedCount;
                    $totalCanceled += $canceledCount;

                    // Ghi dữ liệu vào file CSV
                    fputcsv($file, [
                        $requestType->request_type_name,
                        $pendingCount,
                        $inProgressCount,
                        $completedCount,
                        $canceledCount
                    ]);
                }

                // Ghi dòng tổng cộng chỉ với tổng số lượng
                $overallTotal = $totalPending + $totalInProgress + $totalCompleted + $totalCanceled;
                fputcsv($file, ['Tổng cộng', $overallTotal]);
                break;

            case 'department':
                // Ghi tiêu đề cho các cột
                fputcsv($file, ['Phòng ban', 'Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);

                // Lấy phòng ban từ request
                $selectedDepartment = $request->input('department', 'all'); // Mặc định là 'all'

                // Lấy danh sách các phòng ban
                if ($selectedDepartment !== 'all') {
                    $departments = Department::where('department_name', $selectedDepartment)->withCount('requests')->get();
                } else {
                    $departments = Department::withCount('requests')->get();
                }

                $totalPending = 0;
                $totalInProgress = 0;
                $totalCompleted = 0;
                $totalCanceled = 0;

                foreach ($departments as $department) {
                    // Đếm số yêu cầu theo từng trạng thái
                    $pendingCount = $department->requests()->where('status', 'Chưa xử lý')->count();
                    $inProgressCount = $department->requests()->where('status', 'Đang xử lý')->count();
                    $completedCount = $department->requests()->where('status', 'Hoàn thành')->count();
                    $canceledCount = $department->requests()->where('status', 'Đã hủy')->count();

                    // Cộng dồn số lượng vào tổng
                    $totalPending += $pendingCount;
                    $totalInProgress += $inProgressCount;
                    $totalCompleted += $completedCount;
                    $totalCanceled += $canceledCount;

                    // Ghi dữ liệu vào file CSV
                    fputcsv($file, [
                        $department->department_name, // Hoặc tên phòng ban tương ứng
                        $pendingCount,
                        $inProgressCount,
                        $completedCount,
                        $canceledCount
                    ]);
                }

                // Ghi dòng tổng cộng chỉ với tổng số lượng
                $overallTotal = $totalPending + $totalInProgress + $totalCompleted + $totalCanceled;
                fputcsv($file, ['Tổng cộng', $overallTotal]);
                break;

            case 'time':
                // Get the start and end dates from the request
                $startDate = Carbon::parse($request->input('start', now()->subMonth()->startOfMonth()));
                $endDate = Carbon::parse($request->input('end', now()->endOfMonth()));

                // Write headers
                fputcsv($file, ['Thời gian', 'Đang xử lý', 'Chưa xử lý', 'Hoàn thành', 'Đã hủy']);

                // Fetch requests within the specified date range
                $requests = HttpRequest::whereBetween('create_at', [$startDate, $endDate])->get();

                // Create an array to hold counts for each day
                $dateCounts = [];
                $dateRange = [];

                // Generate all dates in the range
                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $formattedDate = $date->format('Y-m-d');
                    $dateCounts[$formattedDate] = [
                        'inProgress' => 0,
                        'pending' => 0,
                        'completed' => 0, // Ensure this key matches your database status
                        'canceled' => 0,   // Ensure this key matches your database status
                    ];
                    $dateRange[] = $formattedDate; // Keep track of all dates
                }

                // Count requests for each date
                foreach ($requests as $request) {
                    $dateKey = Carbon::parse($request->create_at)->format('Y-m-d');
                    if (isset($dateCounts[$dateKey])) {
                        // Adjust the key to match the actual status values in your database
                        switch ($request->status) {
                            case 'Chưa xử lý':
                                $dateCounts[$dateKey]['pending']++;
                                break;
                            case 'Đang xử lý':
                                $dateCounts[$dateKey]['inProgress']++;
                                break;
                            case 'Hoàn thành':
                                $dateCounts[$dateKey]['completed']++;
                                break;
                            case 'Đã hủy':
                                $dateCounts[$dateKey]['canceled']++;
                                break;
                        }
                    }
                }

                // Write counts to CSV for each date
                foreach ($dateRange as $date) {
                    $counts = $dateCounts[$date];
                    fputcsv($file, [
                        $date,
                        $counts['inProgress'],
                        $counts['pending'],
                        $counts['completed'],
                        $counts['canceled']
                    ]);
                }

                // Prepare total counts
                $totalCounts = [
                    'pending' => 0,
                    'inProgress' => 0,
                    'completed' => 0,
                    'canceled' => 0
                ];

                // Aggregate totals
                foreach ($dateCounts as $counts) {
                    $totalCounts['pending'] += $counts['pending'];
                    $totalCounts['inProgress'] += $counts['inProgress'];
                    $totalCounts['completed'] += $counts['completed'];
                    $totalCounts['canceled'] += $counts['canceled'];
                }

                // Write overall totals
                fputcsv($file, ['Tổng cộng', $totalCounts['inProgress'], $totalCounts['pending'], $totalCounts['completed'], $totalCounts['canceled']]);
                break;

            default:
                fclose($file);
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        fclose($file); // Đóng file

        // Thêm thông báo thành công vào session
        session()->flash('message', 'File CSV đã được lưu thành công.');

        // Tải xuống file CSV
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function formatVietnameseName($name)
    {
        // Đảm bảo tên được định dạng đúng
        return mb_convert_case(trim($name), MB_CASE_TITLE, "UTF-8");
    }
}
