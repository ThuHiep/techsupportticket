<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as SupportRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\RequestType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequestController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu hỗ trợ kỹ thuật
     */
    public function index(HttpRequest $request)
    {
        $template = 'admin.request.index';
        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

        // Lấy các input từ request
        $subject = $request->input('subject'); // Lấy input 'subject'
        $searchField = $request->input('search_field');
        $customerId = $request->input('customer_id');
        $departmentId = $request->input('department_id');
        $requestDate = $request->input('request_date_search'); // Đổi tên để khớp với Blade
        $statusFilter = $request->input('status_search'); // Đổi tên để khớp với Blade
        $requestTypeId = $request->input('request_type_id'); // Thêm input 'request_type_id'

        // Định nghĩa các trạng thái có sẵn bằng tiếng Việt
        $statuses = ['Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy'];

        // Truy vấn các yêu cầu kèm theo quan hệ với Customer, Department, và RequestType
        $query = SupportRequest::with(['customer', 'department', 'requestType']);

        $searchPerformed = false;
        $searchType = null;
        $search = '';
        $additionalSearchType = null;
        $additionalSearchValue = null;

        // Xử lý tìm kiếm bằng subject
        if (!empty($subject)) {
            $query->where('subject', 'like', '%' . $subject . '%');
            $searchPerformed = true;
            $searchType = 'subject';
            $search = $subject;
        }

        // Xử lý tìm kiếm bổ sung
        if (!empty($searchField)) {
            switch ($searchField) {
                case 'customer':
                    if (!empty($customerId)) {
                        $query->where('customer_id', $customerId);
                        $searchPerformed = true;
                        $customer = Customer::find($customerId);
                        $additionalSearchType = 'customer';
                        $additionalSearchValue = $customer ? $customer->full_name : 'N/A';
                    }
                    break;
                case 'department':
                    if (!empty($departmentId)) {
                        $query->where('department_id', $departmentId);
                        $searchPerformed = true;
                        $department = Department::find($departmentId);
                        $additionalSearchType = 'department';
                        $additionalSearchValue = $department ? $department->department_name : 'N/A';
                    }
                    break;
                case 'request_date':
                    if (!empty($requestDate)) {
                        $query->whereDate('received_at', $requestDate);
                        $searchPerformed = true;
                        $formattedDate = Carbon::parse($requestDate)->format('d/m/Y');
                        $additionalSearchType = 'request_date';
                        $additionalSearchValue = $formattedDate;
                    }
                    break;
                case 'status':
                    if (!empty($statusFilter)) {
                        $query->where('status', $statusFilter);
                        $searchPerformed = true;
                        $additionalSearchType = 'status';
                        $additionalSearchValue = $statusFilter;
                    }
                    break;
                case 'request_type':
                    if (!empty($requestTypeId)) {
                        $query->where('request_type_id', $requestTypeId);
                        $searchPerformed = true;
                        $requestType = RequestType::find($requestTypeId);
                        $additionalSearchType = 'request_type';
                        $additionalSearchValue = $requestType ? $requestType->request_type_name : 'N/A';
                    }
                    break;
                default:
                    // Không làm gì nếu không khớp
                    break;
            }
        }

        // Phân trang kết quả với 10 yêu cầu mỗi trang và giữ lại các tham số truy vấn
        $requests = $query->paginate(4)->appends($request->all());

        // Lấy tổng số kết quả
        $count = $requests->total();

        // Lấy danh sách cho các dropdown
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::where('status', 'active')->get(); // Chỉ lấy loại yêu cầu đang hoạt động

        // Truyền thêm các biến vào view
        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'requests',
            'statuses',
            'count',
            'customers',
            'departments',
            'requestTypes',
            'searchPerformed',
            'searchType',
            'search',
            'additionalSearchType',
            'additionalSearchValue'
        ));
    }



    /**
     * Hiển thị form tạo yêu cầu mới
     */
    public function create()
    {
        $template = 'admin.request.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        // Lặp đến khi tìm được mã không trùng lặp
        do {
            $randomNumber = mt_rand(1, 9999999);
            $nextId = 'RQ' . str_pad($randomNumber, 8, '0', STR_PAD_LEFT);
            $exists = SupportRequest::where('request_id', $nextId)->exists();
        } while ($exists); // Nếu tồn tại mã này, sinh lại

        // Lấy danh sách khách hàng, phòng ban, và loại yêu cầu để tạo các lựa chọn trong form
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::all();

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'nextId', 'customers', 'departments', 'requestTypes'));
    }

    /**
     * Lưu yêu cầu mới vào cơ sở dữ liệu
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'request_id' => 'required|unique:request,request_id',
            'customer_id' => 'required|exists:customer,customer_id',
            'department_id' => 'required|exists:department,department_id',
            'request_type_id' => 'required|exists:request_type,request_type_id',
            'subject' => 'required|max:255',
            'description' => 'required',
            'create_at' => 'required|date',
            // Loại bỏ 'status' khỏi validation
            // Loại bỏ 'resolved_at' khỏi validation vì đã loại bỏ trường từ form
        ]);

        SupportRequest::create([
            'request_id' => $request->input('request_id'),
            'customer_id' => $request->input('customer_id'),
            'department_id' => $request->input('department_id'),
            'request_type_id' => $request->input('request_type_id'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'create_at' => $request->input('create_at'),
            'resolved_at' => null, // Đặt mặc định là null
            'status' => 'Chưa xử lý', // Đặt mặc định là "Chưa xử lý"
        ]);

        return redirect()->route('request.index')->with('success', 'Yêu cầu đã được thêm thành công!');
    }




    /**
     * Hiển thị form chỉnh sửa yêu cầu
     */
    public function edit($request_id)
    {
        $template = 'admin.request.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $requestData = SupportRequest::findOrFail($request_id);

        // Chỉ lấy khách hàng có status là "active"
        $customers = Customer::where('status', 'active')->get();

        $departments = Department::all();
        $requestTypes = RequestType::all();

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'requestData', 'customers', 'departments', 'requestTypes'));
    }

    /**
     * Cập nhật yêu cầu trong cơ sở dữ liệu
     */
    public function update(HttpRequest $request, $request_id)
    {
        $supportRequest = SupportRequest::findOrFail($request_id);

        $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'department_id' => 'required|exists:department,department_id',
            'request_type_id' => 'required|exists:request_type,request_type_id',
            'subject' => 'required|max:255',
            'description' => 'required',
            'create_at' => 'required|date',
            'status' => 'required|in:Chưa xử lý,Đang xử lý,Hoàn thành,Đã hủy',
        ]);

        $supportRequest->update([
            'customer_id' => $request->input('customer_id'),
            'department_id' => $request->input('department_id'),
            'request_type_id' => $request->input('request_type_id'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'create_at' => $request->input('create_at'),
            'resolved_at' => $request->input('resolved_at'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('request.index')->with('success', 'Thông tin yêu cầu đã được cập nhật!');
    }


    /**
     * Xóa yêu cầu khỏi cơ sở dữ liệu
     */
    public function destroy($request_id)
    {
        $supportRequest = SupportRequest::findOrFail($request_id);
        $supportRequest->delete();

        return redirect()->route('request.index')->with('success', 'Yêu cầu đã được xóa thành công!');
    }
}
