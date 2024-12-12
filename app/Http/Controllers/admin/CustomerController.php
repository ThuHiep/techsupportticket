<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
use App\Mail\CustomerUpdated;
use App\Models\Customer; // Import Model Customer
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\CustomerCreated;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.customer.index';
        $search = $request->input('search');

        // Truy vấn khách hàng có status là 'active'
        $customers = Customer::with('user')
            ->where('status', 'active')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customer_id', 'LIKE', "%$search%")
                        ->orWhere('full_name', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('email', 'LIKE', "%$search%");
                        });
                });
            })
            ->paginate(3);

        // Tạo thông báo nếu có kết quả tìm kiếm
        $message = null;
        if ($customers->count() > 0) {
            $message = "Tìm thấy " . $customers->count() . " khách hàng có tên là '{$search}'";
        }

        return view('admin.dashboard.layout', compact('template', 'customers', 'message'));
    }


    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        $customers = Customer::with('user')->get(); // Load quan hệ user để lấy email

        // Sinh customer_id ngẫu nhiên
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);


        // Sinh username ngẫu nhiên
        $username = 'user' . mt_rand(100000, 999999);
        while (User::where('username', $username)->exists()) {
            $username = 'user' . mt_rand(100000, 999999);
        }

        // Sinh password ngẫu nhiên
        $password = Str::random(12); // Chuỗi 12 ký tự ngẫu nhiên

        // Truyền các giá trị vào view
        $template = 'admin.customer.create';
        return view('admin.dashboard.layout', compact('template', 'randomId', 'username', 'password', 'customers'));
    }


    // Hiển thị form chỉnh sửa khách hàng
    public function edit($customer_id)
    {
        $template = 'admin.customer.edit';
        $customers = Customer::findOrFail($customer_id);
        return view('admin.dashboard.layout', compact('template', 'customers'));
    }
    public function update(Request $request, $customer_id)
    {

        $customer = Customer::findOrFail($customer_id);
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
        $customer->date_of_birth = $request['date_of_birth'] ?? null;
        $customer->gender = $request['gender'] ?? null;
        $customer->phone = $request['phone'] ?? null;
        $customer->address = $request['address'] ?? null;
        $customer->profile_image = $profileImagePath;
        $customer->software = $request['software'] ?? null;
        $customer->website = $request['website'] ?? null;
        $customer->company = $request['company'] ?? null;
        $customer->email = $request['email'] ?? null;
        $customer->tax_id = $request['tax_id'] ?? null;
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

        // Sinh các giá trị ngẫu nhiên như trước
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);
        $randuserID = 'ND' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);
        $username = 'user' . mt_rand(100000, 999999);
        $password = Str::random(12);

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
                ->with('success', 'Khách hàng đã được thêm thành công! Tài khoản: ' . $username . ', Mật khẩu: ' . $password . ' và email đã được gửi.');
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

        if ($customer) {
            $customer->status = 'inactive'; // Đánh dấu tài khoản là không duyệt
            $customer->save();
            // Gửi email thông báo
            Mail::to($customer->user->email)->send(new AccountRejected($customer));

            return redirect()->route('customer.index')->with([
                'error' => 'Tài khoản đã bị từ chối và email thông báo đã được gửi!',
                'notification_duration' => 500 // thời gian hiển thị thông báo (ms)
            ]);
        }

        return redirect()->route('customer.index')->with('error', 'Không tìm thấy khách hàng');
    }

    public function pendingCustomers()
    {
        $template = 'admin.customer.pending';

        // Lọc khách hàng có status là null và lấy thông tin user liên quan
        $customers = Customer::whereNull('status')
            ->with('user')
            ->paginate(4);

        return view('admin.dashboard.layout', compact('template', 'customers'));
    }


}
