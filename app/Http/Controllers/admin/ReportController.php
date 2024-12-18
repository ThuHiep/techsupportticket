<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Lấy loại yêu cầu từ cơ sở dữ liệu với số lượng yêu cầu
        $requestTypes = RequestType::withCount('requests')->get();

        // Truy vấn để lấy số liệu yêu cầu theo ngày
        $query = DB::table('request')
            ->select(DB::raw('DAY(create_at) as day'), 'request_type_id', DB::raw('COUNT(request_id) as count'))
            ->groupBy(DB::raw('DAY(create_at), request_type_id'));

        $data = $query->get()->groupBy('request_type_id');

        // Chuyển đổi dữ liệu thành định dạng mong muốn
        $response = [];
        foreach ($data as $requestTypeId => $items) {
            $counts = array_fill(0, 31, 0);
            foreach ($items as $item) {
                $counts[$item->day - 1] = $item->count;
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

        return view('admin.statistical.static_index', compact('response', 'requestTypes'));
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
        $period = $request->query('period');
        $data = [];

        switch ($period) {
            case 'today':
                // Lấy dữ liệu cho 7 ngày qua
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i)->format('Y-m-d');
                    $data[$date] = $this->getRequestsCountByDate($date);
                }
                break;

            case 'monthly':
                // Lấy dữ liệu cho 30 ngày qua
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i)->format('Y-m-d');
                    $data[$date] = $this->getRequestsCountByDate($date);
                }
                break;

            case 'yearly':
                // Lấy dữ liệu cho 10 năm qua
                for ($i = 0; $i < 10; $i++) {
                    $year = Carbon::now()->subYears($i)->format('Y');
                    $data[$year] = $this->getRequestsCountByYear($year);
                }
                break;
        }

        return response()->json($data);
    }

    private function getRequestsCountByDate($date)
    {
        // Thay thế bằng logic để lấy số yêu cầu cho ngày
        return rand(1, 100); // Ví dụ: số yêu cầu ngẫu nhiên
    }

    private function getRequestsCountByYear($year)
    {
        // Thay thế bằng logic để lấy số yêu cầu cho năm
        return rand(100, 1000); // Ví dụ: số yêu cầu ngẫu nhiên
    }
}
