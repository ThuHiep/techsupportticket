<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer; // Import Model Customer

class DashboardController extends Controller
{
    public function __construct()
    {
        // Constructor logic
    }

    public function index()
    {
        $config = $this->config();
    
        // Số khách hàng hôm nay
        $totalCustomersToday = Customer::whereDate('create_at', now()->toDateString())->count();
    
        // Số khách hàng ngày hôm qua
        $totalCustomersYesterday = Customer::whereDate('create_at', now()->subDay()->toDateString())->count();
    
        // Tính phần trăm thay đổi so với hôm qua
        if ($totalCustomersYesterday == 0) {
            $percentageChange = $totalCustomersToday > 0 ? '100%+' : '0%';
        } else {
            $percentageChange = (($totalCustomersToday - $totalCustomersYesterday) / $totalCustomersYesterday) * 100;
        }
    
        $template = 'backend.dashboard.home.index';
    
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'totalCustomersToday',
            'percentageChange'
        ));
    }
    

    

    private function config()
    {
        return [
            'js' => [
                'backend/js/plugins/flot/jquery.flot.js',
                'backend/js/plugins/flot/jquery.flot.tooltip.min.js',
                'backend/js/plugins/flot/jquery.flot.spline.js',
                'backend/js/plugins/flot/jquery.flot.resize.js',
                'backend/js/plugins/flot/jquery.flot.pie.js',
                'backend/js/plugins/flot/jquery.flot.symbol.js',
                'backend/js/plugins/flot/jquery.flot.time.js',
                'backend/js/plugins/peity/jquery.peity.min.js',
                'backend/js/demo/peity-demo.js',
                'backend/js/inspinia.js',
                'backend/js/plugins/pace/pace.min.js',
                'backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                'backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                'backend/js/plugins/easypiechart/jquery.easypiechart.js',
                'backend/js/plugins/sparkline/jquery.sparkline.min.js',
                'backend/js/demo/sparkline-demo.js',
            ],
        ];
    }
}
