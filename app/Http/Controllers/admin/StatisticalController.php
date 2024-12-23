<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{
    public function index()
    {
        // Fetch all departments
        $departments = Department::all();

        // Initialize an array to hold department data
        $departmentData = [];

        // Loop through each department to gather request statistics
        foreach ($departments as $department) {
            // Debugging: Check the department ID and name
            //dd($department->department_id, $department->department_name);

            $departmentData[$department->department_name] = [
                'Đang xử lý' => DB::table('request')
                    ->where('department_id', $department->department_id) // Correct foreign key
                    ->where('status', 'Đang xử lý')
                    ->count(),
                'Chưa xử lý' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Chưa xử lý')
                    ->count(),
                'Hoàn thành' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Hoàn thành')
                    ->count(),
                'Đã hủy' => DB::table('request')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'Đã hủy')
                    ->count(),
            ];
        }
        //dd($departmentData);
        // Call the method to get time-based statistics
        $timeData = $this->getTimeBasedStatistics();

        //dd($departmentData, $timeData); // This will show what is being sent to the view
        // Pass the data to the view
        return view('admin.statistical.static_index', compact('departmentData', 'timeData'));
    }

    protected function getTimeBasedStatistics()
    {
        $timeData = [];

        // Get data for each time period
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

        // Initialize an array for each day of the month with 0
        $days = [];
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $days[$date->format('Y-m-d')] = 0; // Initialize with 0
        }

        // Fetch actual request counts
        $dailyStats = DB::table('request')
            ->select(DB::raw("DATE_FORMAT(create_at, '%Y-%m-%d') as period"), DB::raw('count(*) as total'))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get();

        // Populate the days array with actual counts
        foreach ($dailyStats as $stat) {
            $days[$stat->period] = $stat->total;
        }

        // Format the final result
        return array_map(function ($total, $period) {
            return ['period' => $period, 'total' => $total];
        }, $days, array_keys($days));
    }

    private function getWeeklyStatistics()
    {
        $weeks = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        // Initialize an array for each week of the year
        for ($date = clone $startDate; $date <= $endDate; $date->addWeek()) {
            $weeks[$date->format('Y-W')] = 0; // Initialize with 0
        }

        // Fetch actual request counts
        $weeklyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year, WEEK(create_at, 1) as week, count(*) as total"))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('year', 'week')
            ->get();

        // Populate the weeks array with actual counts
        foreach ($weeklyStats as $stat) {
            $weeks[$stat->year . '-' . str_pad($stat->week, 2, '0', STR_PAD_LEFT)] = $stat->total;
        }

        // Format the final result
        return array_map(function ($total, $period) {
            return ['period' => $period, 'total' => $total];
        }, $weeks, array_keys($weeks));
    }

    private function getMonthlyStatistics()
    {
        // Similar logic for months
        $months = [];
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = 0; // Initialize with 0
        }

        $monthlyStats = DB::table('request')
            ->select(DB::raw("MONTH(create_at) as month, count(*) as total"))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('month')
            ->get();

        foreach ($monthlyStats as $stat) {
            $months[$stat->month] = $stat->total;
        }

        return array_map(function ($total, $month) {
            return ['period' => $month, 'total' => $total];
        }, $months, array_keys($months));
    }

    private function getYearlyStatistics()
    {
        // Similar logic for years
        $years = [];
        for ($year = 2020; $year <= 2030; $year++) {
            $years[$year] = 0; // Initialize with 0
        }

        $yearlyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year, count(*) as total"))
            ->whereBetween('create_at', ['2020-01-01', '2030-12-31'])
            ->groupBy('year')
            ->get();

        foreach ($yearlyStats as $stat) {
            $years[$stat->year] = $stat->total;
        }

        return array_map(function ($total, $year) {
            return ['period' => $year, 'total' => $total];
        }, $years, array_keys($years));
    }
}
