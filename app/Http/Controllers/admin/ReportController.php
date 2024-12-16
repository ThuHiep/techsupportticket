<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Lấy loại yêu cầu từ cơ sở dữ liệu
        $requestTypes = RequestType::all();

        // Truy vấn để lấy số liệu yêu cầu theo ngày
        $query = DB::table('request') // Đảm bảo tên bảng là đúng
        ->select(DB::raw('DAY(create_at) as day'), 'request_type_id', DB::raw('COUNT(request_id) as count'))
            ->groupBy(DB::raw('DAY(create_at), request_type_id'));

        $data = $query->get()->groupBy('request_type_id');

        // Chuyển đổi dữ liệu thành định dạng mong muốn
        $response = [];
        foreach ($data as $requestTypeId => $items) {
            $counts = array_fill(0, 31, 0); // Giả định 31 ngày trong tháng
            foreach ($items as $item) {
                $counts[$item->day - 1] = $item->count; // Đếm số yêu cầu theo ngày
            }

            // Tìm kiếm tên loại yêu cầu
            $requestType = $requestTypes->firstWhere('id', $requestTypeId);
            if ($requestType) { // Kiểm tra xem loại yêu cầu có tồn tại không
                $response[] = [
                    'request_type_name' => $requestType->request_type_name,
                    'counts' => $counts
                ];
            } else {
                // Xử lý nếu không tìm thấy loại yêu cầu (nếu cần)
                // Ví dụ: bạn có thể ghi log hoặc thêm vào một thông báo
                \Log::warning("Request type ID {$requestTypeId} not found.");
            }
        }

        return view('admin.statistical.static_index', compact('response'));
    }

    public function getRequests(Request $request)
    {
        $query = DB::table('request') // Đảm bảo tên bảng là đúng
        ->select(DB::raw('DAY(create_at) as day'), 'request_type_name', DB::raw('COUNT(request_id) as count'))
            ->groupBy(DB::raw('DAY(create_at), request_type_name'));

        $data = $query->get()->groupBy('request_type_name');

        // Chuyển đổi dữ liệu thành định dạng mong muốn
        $response = [];
        foreach ($data as $requestType => $items) {
            $counts = array_fill(0, 31, 0); // Giả định 31 ngày trong tháng
            foreach ($items as $item) {
                $counts[$item->day - 1] = $item->count; // Đếm số yêu cầu theo ngày
            }
            $response[] = [
                'request_type_name' => $requestType,
                'counts' => $counts
            ];
        }

        return response()->json($response);
    }
}
