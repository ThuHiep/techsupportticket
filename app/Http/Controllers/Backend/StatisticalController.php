<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  // Thêm dòng này để sử dụng DB\
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    public function index(Request $request)
    {
        $template = 'backend.statistical.index';

        // Lấy thông tin ngày bắt đầu và ngày kết thúc từ request
        $startDate = $request->input('startDate');  // Lấy giá trị từ input 'startDate'
        $endDate = $request->input('endDate');      // Lấy giá trị từ input 'endDate'

        // Lấy số lượng yêu cầu theo loại yêu cầu, có thể lọc theo ngày nếu có
        $query = DB::table('request_type')
            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
            ->where('request_type.status', 'active');

        if ($startDate && $endDate) {
            // Lọc theo ngày nếu người dùng nhập ngày bắt đầu và ngày kết thúc
            $query->whereBetween('request.create_at', [$startDate, $endDate]);
        }

        $requestTypes = $query->groupBy('request_type.request_type_name')->get();

        return view('backend.dashboard.layout', compact('template', 'requestTypes', 'startDate', 'endDate'));
    }

}
