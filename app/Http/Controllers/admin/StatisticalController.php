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

        // Pass the data to the view
        return view('admin.statistical.static_index', compact('departmentData'));
    }
}
