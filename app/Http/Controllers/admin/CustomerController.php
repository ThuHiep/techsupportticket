<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer; // Import Model Customer
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.customer.index';
        $search = $request->input('search');

        $customers = Customer::with('user')
            ->when($search, function ($query) use ($search) {
                return $query->where('customer_id', 'LIKE', "%$search%")
                    ->orWhere('full_name', 'LIKE', "%$search%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('email', 'LIKE', "%$search%");
                    });
            })
            ->whereNull('status')
            ->paginate(4);

        return view('admin.dashboard.layout', compact('template', 'customers'));
    }


    // Hiển thị form tạo khách hàng mới
    public function create()
    {
        $customers = Customer::with('user')->get(); // Load quan hệ user để lấy email
        // Sinh customer_id ngẫu nhiên
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);

        // Sinh tax_id ngẫu nhiên (10 chữ số)
        $taxId = str_pad(mt_rand(0, 999999999), 9, STR_PAD_LEFT);

        // Truyền customer_id, tax_id vào view
        $template = 'admin.customer.create';
        return view('admin.dashboard.layout', compact('template', 'randomId', 'taxId', 'customers'));
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


    // Lưu khách hàng mới
    public function store(Request $request)
    {
        // Thực hiện kiểm tra (validation) các trường dữ liệu
//        $validated = $request->validate([
//            'user_id' => 'required|exists:user,id', // Kiểm tra user_id phải tồn tại trong bảng users
//            'full_name' => 'required|string|max:255', // Kiểm tra full_name không được bỏ trống, là chuỗi và không quá 255 ký tự
//            'email' => 'required|email|unique:customers,email', // Kiểm tra email phải hợp lệ và chưa tồn tại trong bảng customers
//            'date_of_birth' => 'required|date|before:today', // Kiểm tra ngày sinh hợp lệ và trước ngày hiện tại
//            'gender' => 'required|in:Nam,Nữ', // Kiểm tra giới tính hợp lệ
//            'phone' => 'nullable|numeric|digits_between:10,15', // Kiểm tra số điện thoại nếu có, phải là số và từ 10 đến 15 chữ số
//            'address' => 'nullable|string|max:500', // Kiểm tra địa chỉ nếu có, là chuỗi và không quá 500 ký tự
//            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kiểm tra nếu có hình ảnh, phải là ảnh với dung lượng không quá 2MB
//            'software' => 'required|string|max:255',
//            'website' => 'required|string|max:255',
//            'company' => 'nullable|string|max:255', // Kiểm tra tên công ty nếu có, là chuỗi và không quá 255 ký tự
//            'tax_id' => 'required|numeric|digits:9|unique:customers,tax_id', // Kiểm tra tax_id phải là số và có 9 chữ số, chưa tồn tại trong bảng customers
//        ]);

        // Sinh customer_id ngẫu nhiên
        $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);

        // Kiểm tra trùng lặp trong database
        while (Customer::where('customer_id', $randomId)->exists()) {
            $randomId = 'KH' . str_pad(mt_rand(1, 99999999), 8, STR_PAD_LEFT);
        }
        // Sinh tax_id ngẫu nhiên (10 chữ số)
        $taxId = str_pad(mt_rand(0, 999999999), 9, STR_PAD_LEFT);

        // Kiểm tra nếu có hình ảnh được upload
        $profileImagePath = null;  // Gán giá trị mặc định nếu không có hình ảnh
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            if ($image->isValid()) {
                $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                $profileImagePath = $imageName;
                $image->move(public_path('admin/img/customer'), $imageName);
            }
        }

        // Tạo khách hàng mới
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->user_id = $request->input('user_id');
        $customer->full_name = $request->input('full_name');
        $customer->date_of_birth = $request->input('date_of_birth');
        $customer->gender = $request->input('gender');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->profile_image = $profileImagePath;
        $customer->software= $request->input('software');
        $customer->website = $request->input('website');
        $customer->company = $request->input('company');
        $customer->tax_id = $taxId;  // Gán giá trị tax_id
        $customer->create_at = now();  // Ngày tạo
        $customer->update_at = now();  // Ngày cập nhật (ban đầu là ngày tạo)
        $customer->save();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Khách hàng đã được thêm thành công!');
    }

    public function approveCustomer($customer_id)
    {
        $customer = Customer::find($customer_id);

        if (!$customer) {
            return redirect()->route('admin.customer.index')
                ->with('error', 'Không tìm thấy khách hàng!');
        }

        if ($customer->status === 'active') {
            return redirect()->route('admin.customer.index')
                ->with('error', 'Khách hàng đã được duyệt trước đó!');
        }

        // Phê duyệt khách hàng
        $customer->status = 'active';
        $customer->save();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Khách hàng đã được phê duyệt thành công!');
    }
}
