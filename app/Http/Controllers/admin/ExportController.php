<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\RequestType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use App\Models\Request; // Đảm bảo bạn đang sử dụng đúng namespace




class ExportController extends Controller
{
    public function export($type)
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

                // Lấy danh sách khách hàng và số lượng yêu cầu của họ
                $customers = Customer::withCount('requests')->get();

                foreach ($customers as $customer) {
                    fputcsv($file, [
                        $customer->customer_id,
                        $this->formatVietnameseName($customer->full_name),
                        $customer->requests_count
                    ]); // Ghi dữ liệu
                }
                break;

            case 'requestType':
                // Ghi tiêu đề cho các cột
                fputcsv($file, ['Request Type', 'Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);

                // Lấy danh sách các loại yêu cầu và số lượng yêu cầu của chúng
                $requestTypes = RequestType::withCount('requests')->get();

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
                fputcsv($file, ['Department', 'Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);

                // Lấy danh sách các phòng ban và số lượng yêu cầu của chúng
                $departments = Department::withCount('requests')->get();

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
                // Xử lý cho loại báo cáo theo thời gian
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
