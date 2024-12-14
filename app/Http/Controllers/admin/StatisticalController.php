<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;  // Thêm dòng này để sử dụng DB\
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.statistical.index';
        $logged_user = Auth::user();
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $month = $request->input('month', 'all');
        $year = $request->input('year', 'all');
        $selectedType = $request->input('requestTypeFilter', 'all');

        $query = DB::table('request_type')
            ->join('request', 'request.request_type_id', '=', 'request_type.request_type_id')
            ->select('request_type.request_type_name', DB::raw('COUNT(request.request_id) as count'))
            ->where('request_type.status', 'active');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereBetween('request.create_at', [$startDate, $endDate]);
        }

        // Additional filters
        if ($month !== 'all') {
            $query->whereMonth('request.create_at', $month);
        }
        if ($year !== 'all') {
            $query->whereYear('request.create_at', $year);
        }

        // Filter by request type
        if ($selectedType !== 'all') {
            $query->where('request_type.request_type_name', $selectedType);
        }

        // Fetch data
        $requestTypes = $query->groupBy('request_type.request_type_name')->get();
        $activeCustomers = Customer::where('status', 'active')->withCount('requests')->get(['customer_id', 'full_name']);
        $customerColors = ['#3498db', '#1abc9c', '#9b59b6', '#e74c3c', '#f1c40f'];

        return view('admin.dashboard.layout', compact('template', 'activeCustomers', 'requestTypes', 'startDate', 'endDate', 'month', 'year', 'customerColors', 'selectedType'));
    }

    public function getRequests()
    {
        $requests = Request::all(['request_id', 'request_type_name']);
        return response()->json($requests);
        return view('admin.dashboard.layout', compact('template', 'logged_user', 'activeCustomers', 'requestTypes', 'startDate', 'endDate', 'month', 'year', 'customerColors'));
    }
}
