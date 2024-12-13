<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as SupportRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\RequestType;

class RequestController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu hỗ trợ kỹ thuật
     */
    public function index(HttpRequest $request)
    {
        $template = 'admin.request.index';
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        // Định nghĩa các trạng thái có sẵn bằng tiếng Việt
        $statuses = ['Chưa xử lý','Đang xử lý', 'Hoàn thành',];

        // Truy vấn các yêu cầu kèm theo quan hệ với Customer, Department, và RequestType
        $requests = SupportRequest::with(['customer', 'department', 'requestType'])
            ->when($search, function ($query) use ($search) {
                return $query->where('request_id', 'LIKE', "%$search%")
                    ->orWhere('subject', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->paginate(10); // Phân trang 10 yêu cầu mỗi trang

        return view('admin.dashboard.layout', compact('template', 'requests', 'statuses'));
    }

    /**
     * Hiển thị form tạo yêu cầu mới
     */
    public function create()
    {
        $template = 'admin.request.create';

        // Lặp đến khi tìm được mã không trùng lặp
        do {
            $randomNumber = mt_rand(1, 9999);
            $nextId = 'RQ' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $exists = SupportRequest::where('request_id', $nextId)->exists();
        } while ($exists); // Nếu tồn tại mã này, sinh lại

        // Lấy danh sách khách hàng, phòng ban, và loại yêu cầu để tạo các lựa chọn trong form
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::all();

        return view('admin.dashboard.layout', compact('template', 'nextId', 'customers', 'departments', 'requestTypes'));
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
            'received_at' => 'required|date',
            'priority' => 'required|in:Thấp,Trung bình,Cao',
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
            'received_at' => $request->input('received_at'),
            'resolved_at' => null, // Đặt mặc định là null
            'priority' => $request->input('priority'),
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
        $requestData = SupportRequest::findOrFail($request_id);

        // Lấy danh sách khách hàng, phòng ban, và loại yêu cầu để tạo các lựa chọn trong form
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::all();

        return view('admin.dashboard.layout', compact('template', 'requestData', 'customers', 'departments', 'requestTypes'));
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
            'received_at' => 'required|date',
            'priority' => 'required|in:Thấp,Trung bình,Cao',
            'status' => 'required|in:Chưa xử lý,Đang xử lý,Đã xử lý,Hoàn thành,Đã hủy',
        ]);

        $supportRequest->update([
            'customer_id' => $request->input('customer_id'),
            'department_id' => $request->input('department_id'),
            'request_type_id' => $request->input('request_type_id'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'received_at' => $request->input('received_at'),
            'resolved_at' => $request->input('resolved_at'),
            'priority' => $request->input('priority'),
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
