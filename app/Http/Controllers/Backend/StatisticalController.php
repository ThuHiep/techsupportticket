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

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $month = $request->input('month', 'all'); // Giá trị mặc định là 'all'
        $year = $request->input('year', 'all');  // Giá trị mặc định là 'all'

        $query = DB::table('request_type')
            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
            ->where('request_type.status', 'active');

        // Lọc theo ngày bắt đầu và kết thúc
        if ($startDate && $endDate) {
            $query->whereBetween('request.create_at', [$startDate, $endDate]);
        }

        // Lấy dữ liệu sau khi lọc
        $requestTypes = $query->groupBy('request_type.request_type_name')->get();

        return view('backend.dashboard.layout', compact('template', 'requestTypes', 'startDate', 'endDate', 'month', 'year'));
    }
}
