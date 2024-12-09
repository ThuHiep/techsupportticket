<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
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
            ->where('status', 'active') // Lọc theo status = 'active'
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

        return view('admin.dashboard.layout', compact('template', 'customers'));
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
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $customer_id, // Chỉ kiểm tra duy nhất email cho customer_id hiện tại
            'phone' => 'required|digits:10|numeric',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Nam,Nữ',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'software' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'company' => 'nullable|string|max:255',
            'tax_id' => 'nullable|numeric',
        ], [
            'full_name.required' => 'Tên khách hàng không được để trống.',
            'full_name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.digits' => 'Số điện thoại phải có đúng 10 chữ số.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các ký tự số.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'date_of_birth.date' => 'Ngày sinh không hợp lệ.',
            'gender.required' => 'Giới tính không được để trống.',
            'profile_image.image' => 'Ảnh đại diện phải là hình ảnh.',
            'profile_image.mimes' => 'Ảnh đại diện phải có định dạng jpg, jpeg, hoặc png.',
            'profile_image.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            'software.string' => 'Software phải là chuỗi ký tự.',
            'website.url' => 'Website không hợp lệ.',
            'company.string' => 'Công ty phải là chuỗi ký tự.',
            'tax_id.numeric' => 'Mã số thuế phải là số.',
        ]);

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
        $customer->full_name = $validatedData['full_name'];
        $customer->date_of_birth = $validatedData['date_of_birth'] ?? null;
        $customer->gender = $validatedData['gender'] ?? null;
        $customer->phone = $validatedData['phone'] ?? null;
        $customer->address = $validatedData['address'] ?? null;
        $customer->profile_image = $profileImagePath;
        $customer->software = $validatedData['software'] ?? null;
        $customer->website = $validatedData['website'] ?? null;
        $customer->company = $validatedData['company'] ?? null;
        $customer->tax_id = $validatedData['tax_id'] ?? null;
        $customer->update_at = now();
        $customer->save();

        // Cập nhật email trong bảng user
        $user = User::findOrFail($customer->user_id); // Tìm user liên quan đến customer
        $user->email = $validatedData['email']; // Cập nhật email
        $user->save();

        return redirect()->route('customer.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
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
            ->with('success', 'Khách hàng và dữ liệu liên kết trong bảng user đã được xóa!');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'phone' => 'required|digits:10|numeric',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Nam,Nữ',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'software' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'company' => 'nullable|string|max:255',
            'tax_id' => 'nullable|numeric',
        ], [
            'full_name.required' => 'Tên khách hàng không được để trống.',
            'full_name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.digits' => 'Số điện thoại phải có đúng 10 chữ số.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các ký tự số.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'date_of_birth.date' => 'Ngày sinh không hợp lệ.',
            'gender.required' => 'Giới tính không được để trống.',
            'profile_image.image' => 'Ảnh đại diện phải là hình ảnh.',
            'profile_image.mimes' => 'Ảnh đại diện phải có định dạng jpg, jpeg, hoặc png.',
            'profile_image.max' => 'Ảnh đại diện không được vượt quá 2MB.',
            'software.string' => 'Software phải là chuỗi ký tự.',
            'website.url' => 'Website không hợp lệ.',
            'company.string' => 'Công ty phải là chuỗi ký tự.',
            'tax_id.numeric' => 'Mã số thuế phải là số.',
        ]);

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
        $user->email = $validatedData['email'];
        $user->role_id = 3;
        $user->save();

        // Tạo khách hàng
        $customer = new Customer();
        $customer->customer_id = $randomId;
        $customer->user_id = $randuserID;
        $customer->full_name = $validatedData['full_name'];
        $customer->date_of_birth = $validatedData['date_of_birth'] ?? null;
        $customer->profile_image = $profileImageName;
        $customer->gender = $validatedData['gender'] ?? null;
        $customer->phone = $validatedData['phone'] ?? null;
        $customer->address = $validatedData['address'] ?? null;
        $customer->software = $validatedData['software'] ?? null;
        $customer->website = $validatedData['website'] ?? null;
        $customer->company = $validatedData['company'] ?? null;
        $customer->tax_id = $validatedData['tax_id'] ?? null;
        $customer->create_at = now();
        $customer->update_at = now();
        $customer->save();

        try {
            Mail::to($validatedData['email'])->send(new CustomerCreated($username, $password, $validatedData['email']));
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
            $customer->status = 'active';  // Đánh dấu tài khoản là đã duyệt
            $customer->save();

            // Gửi email thông báo
            Mail::to($customer->user->email)->send(new AccountApproved($customer));

            return redirect()->route('customer.index')->with([
                'success' => 'Tài khoản đã được duyệt và email thông báo đã được gửi!',
                'notification_duration' => 500 // thời gian hiển thị thông báo (ms)
            ]);
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
