<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Request; // Alias your model to avoid conflict
use App\Models\RequestType;
use Illuminate\Http\Request as HttpRequest; // Use an alias for the HTTP Request class
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function index(HttpRequest $request)
    {
        // Fetch all departments
        $departments = Department::all();
        \Log::info('Departments:', $departments->toArray());

        // Fetch all request types
        $requestTypes = RequestType::all();
        \Log::info('Request Types:', $requestTypes->toArray());

        // Initialize an array to hold department data
        $departmentData = [];

        // Get all unique statuses from the Request model
        $statuses = Request::distinct()->pluck('status');

        // Loop through each department to gather request statistics
        foreach ($departments as $department) {
            $departmentData[$department->department_name] = [];
            foreach ($statuses as $status) {
                $departmentData[$department->department_name][$status] =
                    $this->getRequestCountByStatus($department->department_id, $status, null);
            }
        }

        // Get time-based statistics
        $timeData = $this->getTimeBasedStatistics();

        // Pass the data to the view
        return view('admin.statistical.static_index',
            compact('departmentData', 'timeData', 'departments', 'requestTypes'));
    }

    private function getRequestCountByStatus($departmentId, $status, $selectedStatus)
    {
        // If a specific status is selected, filter by that; otherwise count all
        return DB::table('request')
            ->where('department_id', $departmentId)
            ->when($selectedStatus, function ($query) use ($selectedStatus) {
                return $query->where('status', $selectedStatus);
            }, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->count();
    }

    protected function getTimeBasedStatistics()
    {
        $timeData = [];
        $timeData['Ngày'] = $this->getDailyStatistics();
        $timeData['Tuần'] = $this->getWeeklyStatistics();
        $timeData['Tháng'] = $this->getMonthlyStatistics();
        $timeData['Năm'] = $this->getYearlyStatistics();
        return $timeData;
    }

    private function getDailyStatistics()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        $days = [];

        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $days[$date->format('Y-m-d')] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $dailyStats = DB::table('request')
            ->select(DB::raw("DATE_FORMAT(create_at, '%Y-%m-%d') as period"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('period', 'status')
            ->get();

        foreach ($dailyStats as $stat) {
            $days[$stat->period][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $period) {
            return ['period' => $period, 'total' => $totals];
        }, $days, array_keys($days));
    }

    private function getWeeklyStatistics()
    {
        $weeks = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();
        for ($date = clone $startDate; $date <= $endDate; $date->addWeek()) {
            $weeks[$date->format('Y-W')] = 0;
        }

        $weeklyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year, WEEK(create_at, 1) as week, count(*) as total"))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('year', 'week')
            ->get();

        foreach ($weeklyStats as $stat) {
            $weeks[$stat->year . '-' . str_pad($stat->week, 2, '0', STR_PAD_LEFT)] = $stat->total;
        }

        return array_map(function ($total, $period) {
            return ['period' => $period, 'total' => $total];
        }, $weeks, array_keys($weeks));
    }

    private function getMonthlyStatistics()
    {
        $months = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $monthlyStats = DB::table('request')
            ->select(DB::raw("MONTH(create_at) as month"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('month', 'status')
            ->get();

        foreach ($monthlyStats as $stat) {
            $months[$stat->month][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $month) {
            return ['period' => $month, 'total' => $totals];
        }, $months, array_keys($months));
    }

    private function getYearlyStatistics()
    {
        $years = [];

        for ($year = 2020; $year <= 2030; $year++) {
            $years[$year] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        $yearlyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year"), 'status', DB::raw('count(*) as total'))
            ->whereBetween('create_at', ['2020-01-01', '2030-12-31'])
            ->groupBy('year', 'status')
            ->get();

        foreach ($yearlyStats as $stat) {
            $years[$stat->year][$stat->status] = $stat->total;
        }

        return array_map(function ($totals, $year) {
            return ['period' => $year, 'total' => $totals];
        }, $years, array_keys($years));
    }
}
