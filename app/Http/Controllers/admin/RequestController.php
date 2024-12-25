<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as SupportRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\RequestType;
use App\Models\RequestHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $statusFilter = $request->input('status_search'); // Chọn từ phần nhập tiêu đề trước đây
        $subject = $request->input('subject'); // Chọn từ phần chọn trạng thái trước đây

        //$subject = $request->input('subject'); // Lấy input 'subject'
        $searchField = $request->input('search_field');
        $customerId = $request->input('customer_id');
        $departmentId = $request->input('department_id');
        $requestDate = $request->input('request_date_search'); // Đổi tên để khớp với Blade
       //$statusFilter = $request->input('status_search'); // Đổi tên để khớp với Blade
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


        $statusFilter = $request->input('status_search'); // Nhận giá trị trạng thái từ form

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter); // Thêm điều kiện lọc theo trạng thái
            $searchPerformed = true; // Đánh dấu rằng tìm kiếm đã được thực hiện
            $additionalSearchType = 'status';
            $additionalSearchValue = $statusFilter; // Lưu trạng thái được chọn để hiển thị
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
                        $query->whereDate('create_at', $requestDate);
                        $searchPerformed = true;
                        $formattedDate = Carbon::parse($requestDate)->format('d/m/Y');
                        $additionalSearchType = 'request_date';
                        $additionalSearchValue = $formattedDate;
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
                case 'subject':
                     if (!empty($subject)) {
                        $query->where('subject', 'like', '%' . $subject . '%');
                        $searchPerformed = true;
                        $searchType = 'subject';
                        $search = $subject;
                    }
                    break;

                default:
                    // Không làm gì nếu không khớp
                    break;
            }
        }

        // Phân trang kết quả với 10 yêu cầu mỗi trang và giữ lại các tham số truy vấn
        $requests = $query->paginate(5)->appends($request->all());

        // Lấy tổng số kết quả
        $count = $requests->total();

        // Lấy danh sách cho các dropdown
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::all();


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
        ]);

        // Tạo yêu cầu mới
        SupportRequest::create([
            'request_id' => $request->input('request_id'),
            'customer_id' => $request->input('customer_id'),
            'department_id' => $request->input('department_id'),
            'request_type_id' => $request->input('request_type_id'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'create_at' => $request->input('create_at'),
            'resolved_at' => null,
            'status' => 'Chưa xử lý',
        ]);

        // Lấy thông tin người tạo (admin)
        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

        // Tạo bản ghi lịch sử đầu tiên với trạng thái "Chưa xử lý"
        RequestHistory::create([
            'request_id' => $request->input('request_id'),
            'changed_by' => $logged_user->employee_id,
            'old_status' => null,
            'new_status' => 'Chưa xử lý',
            'note' => 'Tạo yêu cầu',
            'changed_at' => now(),
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
        $requestData = SupportRequest::with('attachment')->findOrFail($request_id); // Tải quan hệ attachment
        $supportRequest = SupportRequest::with(['attachment', 'history'])->findOrFail($request_id); // Tải quan hệ attachment và histories


        // Chỉ lấy khách hàng có status là "active"
        $customers = Customer::where('status', 'active')->get();

        $departments = Department::all();
        $requestTypes = RequestType::all();

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'requestData', 'supportRequest', 'customers', 'departments', 'requestTypes'));
    }

    /**
     * Cập nhật yêu cầu trong cơ sở dữ liệu
     */
    public function update(HttpRequest $request, $request_id)
    {
        $supportRequest = SupportRequest::with('attachment')->findOrFail($request_id);

        // Validate dữ liệu
        $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'department_id' => 'required|exists:department,department_id',
            'request_type_id' => 'required|exists:request_type,request_type_id',
            'subject' => 'required|max:255',
            'description' => 'required',
            'create_at' => 'required|date',
            'status' => 'required|in:Chưa xử lý,Đang xử lý,Hoàn thành,Đã hủy',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:40960', // 40 MB
        ]);

        // Lưu trạng thái cũ để tạo lịch sử
        $oldStatus = $supportRequest->status;
        $newStatus = $request->input('status');

        // Cập nhật thông tin yêu cầu
        $supportRequest->update([
            'customer_id' => $request->input('customer_id'),
            'department_id' => $request->input('department_id'),
            'request_type_id' => $request->input('request_type_id'),
            'subject' => $request->input('subject'),
            'description' => $request->input('description'),
            'create_at' => $request->input('create_at'),
            'resolved_at' => $newStatus === 'Hoàn thành' ? now() : $supportRequest->resolved_at,
            'status' => $newStatus,
        ]);

        // Lấy thông tin người cập nhật (admin)
        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

        // Tạo bản ghi lịch sử mới nếu trạng thái thay đổi
        if ($oldStatus !== $newStatus) {
            RequestHistory::create([
                'request_id' => $request_id,
                'changed_by' => $logged_user->employee_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $request->input('note', 'Cập nhật trạng thái yêu cầu'),
                'changed_at' => now(),
            ]);
        }

        // Xử lý file đính kèm mới hoặc cập nhật file đính kèm hiện tại
        if ($request->hasFile('attachments')) {
            // Nếu đã có file đính kèm, xóa file cũ trước khi upload mới
            if ($supportRequest->attachment) {
                // Xóa file từ storage
                if (Storage::disk('public')->exists($supportRequest->attachment->file_path)) {
                    Storage::disk('public')->delete($supportRequest->attachment->file_path);
                }
                // Xóa bản ghi trong cơ sở dữ liệu
                $supportRequest->attachment->delete();
            }

            $file = $request->file('attachments');
            $filename = $file->getClientOriginalName();
            $filePath = $file->store('attachments', 'public'); // Lưu vào thư mục 'storage/app/public/attachments'
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();

            // Tạo mới bản ghi Attachment
            $supportRequest->attachment()->create([
                'attachment_id' => uniqid('ATT_'), // Tạo ID duy nhất, đảm bảo không vượt quá kích thước cột
                'filename' => $filename,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ]);
        }

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



    public function getPendingRequestsByDate(HttpRequest $request)
    {

            $date = $request->input('date', now()->toDateString());
            $pendingRequests = SupportRequest::whereDate('create_at', $date)
                ->where('status', 'Chưa xử lý')
                ->count();

            return response()->json(['count' => $pendingRequests]);

    }


}
