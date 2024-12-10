<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer; // Import Model Customer
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
//    public function index(Request $request)
//    {
//        $search = $request->input('search');
//
//        $customers = Customer::where('status', 'active') // Chỉ lấy khách hàng đã được phê duyệt
//        ->when($search, function ($query, $search) {
//            return $query->where('full_name', 'LIKE', '%' . $search . '%');
//        })
//            ->paginate(10); // Phân trang
//
//        return view('admin.customer.index', compact('customers'));
//    }

    public function index(Request $request)
    {
        $template = 'admin.customer.index';
        $search = $request->input('search');

        // Truy vấn khách hàng có status là 'active'
        $customers = Customer::with('user')
            ->when($search, function ($query) use ($search) {
                return $query->where('customer_id', 'LIKE', "%$search%")
                    ->orWhere('full_name', 'LIKE', "%$search%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('email', 'LIKE', "%$search%");
                    });
            })
            ->where('status', 'active')  // Lọc theo status = 'active'
            ->paginate(4);

        // Truy vấn khách hàng có status là NULL (Chờ duyệt)
        $pendingCustomers = Customer::with('user')
            ->when($search, function ($query) use ($search) {
                return $query->where('customer_id', 'LIKE', "%$search%")
                    ->orWhere('full_name', 'LIKE', "%$search%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('email', 'LIKE', "%$search%");
                    });
            })
            ->whereNull('status')  // Lọc theo status = NULL (Chờ duyệt)
            ->paginate(3);

        return view('admin.dashboard.layout', compact('template', 'customers', 'pendingCustomers'));
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
        // Lấy thông tin khách hàng cũ
        $customer = Customer::findOrFail($customer_id);

        // Kiểm tra nếu có hình ảnh được upload
        $profileImagePath = $customer->profile_image;  // Lưu ảnh cũ, nếu có
        if ($request->hasFile('profile_image')) {
            // Xóa ảnh cũ nếu có
            if ($profileImagePath && file_exists(public_path('admin/img/customer/' . $profileImagePath))) {
                unlink(public_path('admin/img/customer/' . $profileImagePath)); // Xóa file ảnh cũ
            }

            // Lưu ảnh mới
            $image = $request->file('profile_image');
            if ($image->isValid()) {
                $imageName = 'update_' . time() . '.' . $image->getClientOriginalExtension();
                $profileImagePath = $imageName;  // Cập nhật đường dẫn ảnh mới
                $image->move(public_path('admin/img/customer'), $imageName);  // Di chuyển ảnh mới vào thư mục
            }
        }

        // Cập nhật thông tin khách hàng
        $customer->full_name = $request->input('full_name');
        $customer->date_of_birth = $request->input('date_of_birth');
        $customer->gender = $request->input('gender');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->profile_image = $profileImagePath; // Cập nhật ảnh đại diện (hoặc ảnh cũ nếu không có ảnh mới)
        $customer->email = $request->input('email');
        $customer->software = $request->input('software');
        $customer->website = $request->input('website');
        $customer->company = $request->input('company');
        $customer->tax_id = $request->input('tax_id');  // Cập nhật giá trị tax_id
        $customer->create_at = now();  // Ngày tạo (vẫn giữ nguyên nếu không cần thay đổi)
        $customer->update_at = now();  // Ngày cập nhật (thay đổi khi cập nhật)

        // Lưu thông tin khách hàng vào database
        $customer->save();

        // Quay lại trang danh sách khách hàng với thông báo thành công
        return redirect()->route('admin.customer.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    // Xóa khách hàng
    public function destroy($customer_id)
    {
        $customers = Customer::findOrFail($customer_id);
        $customers->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Khách hàng đã được xóa!');
    }


    public function store(Request $request)
    {
        // Sinh customer_id ngẫu nhiên
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);
        // Sinh customer_id ngẫu nhiên
        $randuserID = 'ND' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);

        // Kiểm tra trùng lặp trong database
        while (Customer::where('customer_id', $randomId)->exists()) {
            $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);
        }

        // Sinh username ngẫu nhiên
        $username = 'user' . mt_rand(100000, 999999);
        while (User::where('username', $username)->exists()) {
            $username = 'user' . mt_rand(100000, 999999);
        }

        // Sinh password ngẫu nhiên
        $password = Str::random(12); // Chuỗi 12 ký tự ngẫu nhiên

        // Lấy email từ request
        $email = $request->input('email');

        // Tạo tài khoản User tương ứng
        $user = new User();
        $user ->user_id = $randuserID;
        $user->username = $username;
        $user->password = bcrypt($password); // Mã hóa mật khẩu
        $user->email = $email;
        $user->role_id = 3; // Gán role_id là 3
        $user->save();

        // Tạo khách hàng mới
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->user_id = $randuserID; // Liên kết với user_id vừa tạo
        $customer->full_name = $request->input('full_name');
        $customer->date_of_birth = $request->input('date_of_birth');
        $customer->gender = $request->input('gender');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->software = $request->input('software');
        $customer->website = $request->input('website');
        $customer->company = $request->input('company');
        $customer->tax_id = $request->input('tax_id');;
        $customer->create_at = now();
        $customer->update_at = now();
        $customer->save();

        // Trả về kết quả
        return redirect()->route('customer.index')
            ->with('success', 'Khách hàng đã được thêm thành công! Tài khoản: ' . $username . ', Mật khẩu: ' . $password);
    }


//    public function approveCustomer($customer_id)
//    {
//        $customer = Customer::find($customer_id);
//
//        if (!$customer) {
//            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy khách hàng!']);
//        }
//
//        if ($customer->status === 'active') {
//            return response()->json(['status' => 'error', 'message' => 'Khách hàng đã được duyệt trước đó!']);
//        }
//
//        // Phê duyệt khách hàng
//        $customer->status = 'active';
//        $customer->save();
//
//        return response()->json(['status' => 'success', 'message' => 'Khách hàng đã được phê duyệt thành công!']);
//    }
}
