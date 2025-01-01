<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
use App\Mail\CustomerUpdated;
use App\Models\Customer;
use App\Models\CustomerFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\CustomerCreated;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.customer.index';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $search = $request->input('search');
        $searchPerformed = $search !== null && $search !== '';

        // Truy vấn khách hàng có status là 'active'
        $customers = Customer::where('status', 'active')
            ->when($search, function ($query) use ($search) {
                return $query->whereRaw("full_name COLLATE utf8_general_ci LIKE ?", ["%$search%"]);
            })
            ->paginate(3);


        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'customers',
            'searchPerformed',
            'search',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        $customers = Customer::with('user')->get(); // Load quan hệ user để lấy email

        // Sinh customer_id ngẫu nhiên và kiểm tra trùng lặp
        $randomId = (string) Str::uuid();

        // Sinh username ngẫu nhiên
        $username = 'user' . mt_rand(100000, 999999);
        while (User::where('username', $username)->exists()) {
            $username = 'user' . mt_rand(100000, 999999);
        }

        // Sinh password ngẫu nhiên với 20 ký tự bao gồm ký tự đặc biệt
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';
        for ($i = 0; $i < 20; $i++) {
            $password .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        // Truyền các giá trị vào view
        $template = 'admin.customer.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'randomId',
            'username',
            'password',
            'customers',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }


    // Hiển thị form chỉnh sửa khách hàng
    public function edit($customer_id)
    {
        $template = 'admin.customer.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $customers = Customer::findOrFail($customer_id);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'customers',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function update(Request $request, $customer_id)
    {

        $customer = Customer::findOrFail($customer_id);

        // Cập nhật thông tin người dùng
        $user = $customer->user; // Lấy người dùng liên kết với khách hàng

        // Cập nhật username nếu có
        if ($request->has('username')) {
            $user->username = $request->input('username');
            $user->save(); // Lưu thay đổi
        }
        // Xử lý ảnh đại diện
        $profileImagePath = $customer->profile_image;
        if ($request->hasFile('profile_image')) {
            if ($profileImagePath && file_exists(public_path('admin/img/customer/' . $profileImagePath))) {
                unlink(public_path('admin/img/customer/' . $profileImagePath));
            }
            $image = $request->file('profile_image');
            $profileImageName = 'update_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('admin/img/customer'), $profileImageName);
            $profileImagePath = $profileImageName;
        }

        // Cập nhật thông tin khách hàng
        $customer->full_name = $request['full_name'];
        $customer->date_of_birth = $request['date_of_birth'];
        $customer->gender = $request['gender'] ?? null;
        $customer->phone = $request['phone'] ?? null;
        $customer->address = $request['address'] ?? null;
        $customer->profile_image = $profileImagePath;
        $customer->software = $request['software'] ?? null;
        $customer->website = $request['website'] ?? null;
        $customer->company = $request['company'] ?? null;
        $customer->email = $request['email'] ?? null;
        $customer->tax_id = $request['tax_id'] ?? null;
        $customer->status = $request['status'] ?? null;
        $customer->update_at = now();
        $customer->save();

        // Gửi email thông báo
        try {
            Mail::to($customer->email)->send(new CustomerUpdated($customer));
            return redirect()->route('customer.index')
                ->with('success', 'Khách hàng đã được cập nhật thành công và email thông báo đã được gửi!');
        } catch (\Exception $e) {
            return redirect()->route('customer.index')
                ->with('error', 'Khách hàng đã được cập nhật, nhưng không thể gửi email. Lỗi: ' . $e->getMessage());
        }
    }

    // Xóa khách hàng
    public function destroy($customer_id)
    {
        // Tìm khách hàng dựa trên customer_id
        $customer = Customer::findOrFail($customer_id);

        // Kiểm tra và xóa ảnh đại diện nếu có
        if ($customer->profile_image) {
            $imagePath = public_path('admin/img/customer/' . $customer->profile_image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Xóa file khỏi thư mục
            }
        }

        // Tìm và xóa người dùng liên kết với khách hàng qua user_id
        if ($customer->user_id) {
            $user = User::find($customer->user_id); // Tìm bản ghi trong bảng users
            if ($user) {
                $user->delete(); // Xóa người dùng
            }
        }

        // Xóa khách hàng khỏi cơ sở dữ liệu
        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Khách hàng đã được xóa!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:customer,email'],
        ], [
            'email.unique' => 'Email đã tồn tại',
        ]);

        // Sinh các giá trị ngẫu nhiên như trước
        $randomId = (string) Str::uuid();
        $randuserID = (string) Str::uuid();
        $username = 'user' . mt_rand(100000, 999999);

        // Sinh password ngẫu nhiên với 20 ký tự bao gồm ký tự đặc biệt
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';
        for ($i = 0; $i < 20; $i++) {
            $password .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        // Xử lý lưu ảnh
        $profileImageName = null;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $profileImageName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('admin/img/customer'), $profileImageName);
        }

        // Tạo tài khoản User
        $user = new User();
        $user->user_id = $randuserID;
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->role_id = 3;
        $user->save();

        // Tạo khách hàng
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->user_id = $randuserID;
        $customer->full_name = $request['full_name'];
        $customer->date_of_birth = $request['date_of_birth'] ?? null;
        $customer->profile_image = $profileImageName;
        $customer->gender = $request['gender'] ?? null;
        $customer->phone = $request['phone'] ?? null;
        $customer->address = $request['address'] ?? null;
        $customer->email = $request['email'] ?? null;
        $customer->software = $request['software'] ?? null;
        $customer->website = $request['website'] ?? null;
        $customer->company = $request['company'] ?? null;
        $customer->tax_id = $request['tax_id'] ?? null;
        $customer->create_at = now();
        $customer->update_at = now();
        $customer->save();

        try {
            Mail::to($request['email'])->send(new CustomerCreated($username, $password, $request['email']));
            return redirect()->route('customer.index')
                ->with('success', 'Thêm khách hàng thành công! Email đã được gửi.');
        } catch (\Exception $e) {
            return redirect()->route('customer.index')
                ->with('error', 'Khách hàng đã được thêm, nhưng không thể gửi email. Lỗi: ' . $e->getMessage());
        }
    }

    //Duyệt tài khoản
    public function approve($customer_id)
    {
        $customer = Customer::find($customer_id);

        if ($customer) {
            // Check status of the user
            if ($customer->user->status === null) {
                $customer->status = 'active';  // Mark account as approved
                $customer->save();

                // Update user status
                $customer->user->status = 'active'; // Mark user as approved
                $customer->user->save();

                // Check if email is available
                if (!empty($customer->email)) {
                    // Send notification email
                    Mail::to($customer->email)->send(new AccountApproved($customer));
                } else {
                    return redirect()->route('customer.index')->with('error', 'Email không hợp lệ.');
                }

                return redirect()->route('customer.index')->with([
                    'success' => 'Tài khoản đã được duyệt và email thông báo đã được gửi!',
                    'notification_duration' => 500 // thời gian hiển thị thông báo (ms)
                ]);
            } else {
                return redirect()->route('customer.index')->with('error', 'Tài khoản không trong trạng thái cần duyệt');
            }
        }

        return redirect()->route('customer.index')->with('error', 'Không tìm thấy khách hàng');
    }

    // Từ chối duyệt
    public function reject($customer_id)
    {
        $customer = Customer::find($customer_id);

        // Check if the customer exists
        if ($customer) {
            // Send notification email if email is available
            if (!empty($customer->email)) {
                Mail::to($customer->email)->send(new AccountRejected($customer));
            }

            // Get the associated user ID and delete the user
            $userId = $customer->user_id; // Assuming you have a user_id field in the Customer model

            // Delete the customer record
            $customer->delete();

            // Delete the associated user record from the User table
            if ($userId) {
                User::find($userId)->delete();
            }

            return redirect()->route('customer.index')->with([
                'success' => 'Tài khoản đã bị từ chối và đã bị xóa!',
                'notification_duration' => 500 // Duration for displaying the notification (ms)
            ]);
        } else {
            return redirect()->route('customer.index')->with('error', 'Không tìm thấy khách hàng.');
        }
    }

    public function pendingCustomers(Request $request)
    {
        $template = 'admin.customer.pending';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        // Xóa khách hàng không duyệt lâu hơn 30 ngày
        Customer::whereNull('status')
            ->where('create_at', '<', now()->subDays(2))
            ->delete();

        // Lấy các tham số tìm kiếm
        $searchName = $request->input('name');
        $searchDate = $request->input('date');

        // Lọc khách hàng có status là null và lấy thông tin user liên quan
        $customers = Customer::whereNull('status')
            ->with('user')
            ->when($searchName, function ($query) use ($searchName) {
                return $query->where('full_name', 'LIKE', "%$searchName%");
            })
            ->when($searchDate, function ($query) use ($searchDate) {
                return $query->whereDate('create_at', $searchDate);
            })
            ->paginate(4);

        // Đếm số kết quả tìm kiếm
        $totalResults = $customers->total();
        $searchPerformed = $searchName || $searchDate;

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'customers',
            'searchPerformed',
            'totalResults',
            'searchName',
            'searchDate',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function getUserList(Request $request)
    {
        $date = $request->input('date', now()->toDateString()); // Nếu không có ngày, sử dụng ngày hôm nay
        $users = Customer::select('customer_id', 'full_name', 'status', 'create_at')
            ->whereNull('status') // Chỉ lấy các tài khoản chưa được phê duyệt
            ->whereDate('create_at', $date) // Lọc theo ngày
            ->get();

        return response()->json($users);
    }
}
