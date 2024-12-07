<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  // Thêm dòng này để sử dụng DB

class StatisticalController extends Controller
{
    public function index(){
        $template = 'backend.statistical.index';
        // Lấy số lượng yêu cầu theo loại yêu cầu từ bảng request và request_type
        $requestTypes = DB::table('request_type')
            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
            ->where('request_type.status', 'active')
            ->groupBy('request_type.request_type_name')
            ->get();


        // Trả về view với dữ liệu khách hàng
        return view('backend.dashboard.layout', compact('template', 'requestTypes'));
    }

}
