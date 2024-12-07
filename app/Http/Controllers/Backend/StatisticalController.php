<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class StatisticalController extends Controller
{
    public function index(){
        $template = 'backend.statistical.index';

        // Trả về view với dữ liệu khách hàng
        return view('backend.dashboard.layout', compact('template'));
    }
}
