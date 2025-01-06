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

        $filePath = $directoryPath . '/report_' . $type . '.csv';
        $file = fopen($filePath, 'w');

        // Ghi BOM để Excel nhận diện mã hóa UTF-8
        fwrite($file, "\xEF\xBB\xBF");

        // Xử lý xuất CSV theo loại báo cáo
        switch ($type) {
            case 'customer':
                fputcsv($file, ['Customer ID', 'Customer Name', 'Request Count']); // Ghi tiêu đề

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
                // Xử lý cho loại báo cáo theo phòng ban
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

        // Chuyển hướng về trang thống kê
        return redirect()->route('statistical.index'); // Đảm bảo route đúng với tên route bạn đã định nghĩa
    }

    private function formatVietnameseName($name)
    {
        // Đảm bảo tên được định dạng đúng
        return mb_convert_case(trim($name), MB_CASE_TITLE, "UTF-8");
    }
}
