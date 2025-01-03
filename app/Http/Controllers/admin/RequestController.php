<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Mail\DepartmentChangedMail;
use App\Mail\RequestResultMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as SupportRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\RequestType;
use App\Models\RequestHistory;
use App\Models\Attachment;
use App\Models\CustomerFeedback;
use App\Models\EmployeeFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RequestController extends Controller
{
    public static function getUnreadRequests()
    {
        // Lấy danh sách request có phản hồi chưa đọc từ khách hàng
        $unreadRequests = Request::whereHas('customerFeedbacks', function ($query) {
            $query->where('is_read', false);
        })
            ->with(['customer', 'customerFeedbacks' => function ($query) {
                $query->where('is_read', false)
                    ->select('request_id', 'created_at');
            }])
            ->get()
            ->map(function ($request) {
                $request->feedback_count = $request->customerFeedbacks->count();
                $request->last_feedback_time = $request->customerFeedbacks->max('created_at')
                    ? Carbon::parse($request->customerFeedbacks->max('created_at')) // Chuyển sang Carbon
                    : null;
                return $request;
            });

        // Tính tổng số request có phản hồi chưa đọc
        $unreadRequestCount = $unreadRequests->count();

        return compact('unreadRequests', 'unreadRequestCount');
    }
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

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

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
            'additionalSearchValue',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }



    /**
     * Hiển thị form tạo yêu cầu mới
     */
    public function create()
    {
        $template = 'admin.request.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $nextId = (string) Str::uuid();


        // Lấy danh sách khách hàng, phòng ban, và loại yêu cầu để tạo các lựa chọn trong form
        $customers = Customer::all();
        $departments = Department::all();
        $requestTypes = RequestType::all();

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'nextId', 'customers', 'departments', 'requestTypes', 'unreadRequests', 'unreadRequestCount'));
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

    private function getFeedback($feedbackModel, $joinModel, $foreignKey, $request_id)
    {
        return $feedbackModel::select(
            "{$feedbackModel->getTable()}.id",
            "{$feedbackModel->getTable()}.request_id",
            "{$joinModel->getTable()}.full_name",
            "{$joinModel->getTable()}.profile_image",
            "{$feedbackModel->getTable()}.message",
            "{$feedbackModel->getTable()}.created_at",
            "user.role_id"
        )
            ->join($joinModel->getTable(), "{$joinModel->getTable()}.{$foreignKey}", '=', "{$feedbackModel->getTable()}.{$foreignKey}")
            ->join('user', 'user.user_id', '=', "{$joinModel->getTable()}.user_id")
            ->where("{$feedbackModel->getTable()}.request_id", $request_id);
    }

    /**
     * Hiển thị form chỉnh sửa yêu cầu
     */
    public function edit($request_id)
    {
        $template = 'admin.request.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $supportRequest = SupportRequest::with(['attachment', 'history'])->findOrFail($request_id); // Tải quan hệ attachment và history

        // Chỉ lấy khách hàng có status là "active"
        $customers = Customer::where('status', 'active')->get();

        $departments = Department::where('status', 'active')->get();
        $requestTypes = RequestType::all();

        // Lấy feedback từ khách hàng
        $customerFeedbacks = $this->getFeedback(new CustomerFeedback(), new Customer(), 'customer_id', $request_id);

        // Lấy feedback từ nhân viên
        $employeeFeedbacks = $this->getFeedback(new EmployeeFeedback(), new Employee(), 'employee_id', $request_id);

        // Kết hợp feedback từ cả hai bảng
        $feedbacks = $customerFeedbacks->unionAll($employeeFeedbacks->toBase())->orderBy('created_at', 'desc')->get();

        CustomerFeedback::where('request_id', $request_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'supportRequest', 'customers', 'departments', 'requestTypes', 'feedbacks', 'unreadRequests', 'unreadRequestCount'));
    }


    /**
     * Cập nhật yêu cầu trong cơ sở dữ liệu
     */
    public function update(HttpRequest $request, $request_id)
    {
        // Tìm yêu cầu cần cập nhật
        $supportRequest = SupportRequest::with(['attachment','department','requestType','customer'])->findOrFail($request_id);

        // Validate dữ liệu
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'department_id' => 'required|exists:department,department_id',
            'request_type_id' => 'required|exists:request_type,request_type_id',
            'subject' => 'required|max:255',
            'description' => 'required',
            'status' => 'required|in:Chưa xử lý,Đang xử lý,Hoàn thành,Đã hủy',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt|max:40960',
        ]);

        // Kiểm tra điều kiện cập nhật trạng thái
        if ($supportRequest->status === 'Chưa xử lý' && $validatedData['department_id']) {
            $validatedData['status'] = 'Đang xử lý';
        }

        if ($supportRequest->department_id !== $validatedData['department_id']) {
            if ($validatedData['department_id'] && $supportRequest->status === 'Chưa xử lý') {
                $validatedData['status'] = 'Đang xử lý';
            }
        }

        // Lưu thông tin yêu cầu mới
        $supportRequest->update([
            'customer_id' => $validatedData['customer_id'],
            'department_id' => $validatedData['department_id'],
            'request_type_id' => $validatedData['request_type_id'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'resolved_at' => $validatedData['status'] === 'Hoàn thành' ? now() : $supportRequest->resolved_at,
            'status' => $validatedData['status'],
        ]);

        $supportRequest->refresh();


        // Nếu trạng thái chuyển sang "Hoàn thành", xóa các phản hồi
        if ($validatedData['status'] === 'Hoàn thành') {
            CustomerFeedback::where('request_id', $request_id)->delete();
            EmployeeFeedback::where('request_id', $request_id)->delete();
        }

        // Nếu phòng ban thay đổi, xóa các phản hồi
        if ($supportRequest->wasChanged('department_id')) {
            CustomerFeedback::where('request_id', $request_id)->delete();
            EmployeeFeedback::where('request_id', $request_id)->delete();

            Mail::to($supportRequest->customer->email)
                ->send(new DepartmentChangedMail($supportRequest));
        }

        if ($supportRequest->wasChanged('status')) {
            // Gửi mail
            Mail::to($supportRequest->customer->email)
                ->send(new RequestResultMail($supportRequest));
        }

        // Lưu lịch sử thay đổi trạng thái nếu trạng thái hoặc phòng ban thay đổi
        if ($supportRequest->wasChanged('status') || $supportRequest->wasChanged('department_id')) {
            $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

            RequestHistory::create([
                'request_id'    => $request_id,
                'changed_by'    => $logged_user->employee_id,
                'old_status'    => $supportRequest->getOriginal('status'),
                'new_status'    => $validatedData['status'],
                'note'          => $request->input('note', 'Cập nhật trạng thái yêu cầu'),
                'changed_at'    => now(),
                'department_id' => $validatedData['department_id'],
            ]);
        }

        // Xử lý file đính kèm nếu có
        if ($request->hasFile('attachments')) {
            if ($supportRequest->attachment) {
                if (Storage::disk('public')->exists($supportRequest->attachment->file_path)) {
                    Storage::disk('public')->delete($supportRequest->attachment->file_path);
                }
                $supportRequest->attachment->delete();
            }

            $file = $request->file('attachments');
            $filename = $file->getClientOriginalName();
            $filePath = $file->store('attachments', 'public');
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();

            $supportRequest->attachment()->create([
                'attachment_id' => (string) Str::uuid(),
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

    public function reply(HttpRequest $request, $request_id)
    {
        $logged_user = Employee::with('user')->where('user_id', Auth::user()->user_id)->first();

        $employee_feedback = new EmployeeFeedback();
        $employee_feedback->request_id = $request_id;
        $employee_feedback->employee_id = $logged_user->employee_id;
        $employee_feedback->message = $request->input('reply_content');
        $employee_feedback->Save();

        return redirect()->route('request.edit', $request_id)->with('success', 'Phản hồi đã được gửi thành công!');
    }
}
