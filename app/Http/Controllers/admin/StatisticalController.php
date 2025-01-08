<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Request; // Alias your model to avoid conflict
use App\Models\RequestType;
use Illuminate\Http\Request as HttpRequest; // Đổi tên cho rõ ràng
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
    public function index()
    {
        $timeData = $this->getTimeBasedStatistics();

        return view('admin.statistical.static_index', compact('timeData'));
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

        // Kiểm tra kết quả của dailyStats
        \Log::info('Daily Stats:', $dailyStats->toArray());

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

        // Tạo mảng tuần
        for ($date = clone $startDate; $date <= $endDate; $date->addWeek()) {
            $weeks[$date->format('Y-W')] = [
                'Đang xử lý' => 0,
                'Chưa xử lý' => 0,
                'Hoàn thành' => 0,
                'Đã hủy' => 0,
            ];
        }

        // Lấy thống kê tuần
        $weeklyStats = DB::table('request')
            ->select(DB::raw("YEAR(create_at) as year, WEEK(create_at, 1) as week, status, count(*) as total"))
            ->whereBetween('create_at', [$startDate, $endDate])
            ->groupBy('year', 'week', 'status')
            ->get();

        // Cập nhật mảng tuần với số liệu
        foreach ($weeklyStats as $stat) {
            $weekKey = $stat->year . '-' . str_pad($stat->week, 2, '0', STR_PAD_LEFT);
            if (isset($weeks[$weekKey])) {
                $weeks[$weekKey][$stat->status] = $stat->total;
            }
        }

        // Trả về dữ liệu theo định dạng mong muốn
        return array_map(function ($totals, $period) {
            return [
                'period' => $period,
                'totals' => $totals,
            ];
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
