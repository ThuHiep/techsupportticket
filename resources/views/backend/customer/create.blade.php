<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
</head>
<body>
<div class="container mt-4">
    <h1>Thêm khách hàng mới</h1>
    <form action="{{ route('backend.customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Hiển thị customer_id (không chỉnh sửa được) -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Mã khách hàng</label>
            <input type="text" id="customer_id" name="customer_id" class="form-control"
                   value="{{ $randomId }}" readonly required>
        </div>

        <!-- Hiển thị tax_id (không chỉnh sửa được) -->
        <div class="mb-3">
            <label for="tax_id" class="form-label">Mã số thuế</label>
            <input type="text" id="tax_id" name="tax_id" class="form-control"
                   value="{{ $taxId }}" readonly required>
        </div>

        <!-- Tên khách hàng (cho phép nhập) -->
        <div class="mb-3">
            <label for="full_name" class="form-label">Tên khách hàng</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>


        <!-- Chọn khách hàng -->
        <div class="mb-3">
            <label for="user_id" class="form-label">Chọn khách hàng</label>
            <select id="user_id" name="user_id" class="form-control" required>
                <option value="" disabled selected>Chọn tên khách hàng</option>
                @foreach ($customers as $customer)
                    <!-- Make sure to pass user_id instead of username -->
                    <option value="{{ $customer->user_id }}">{{ $customer->user->username }}</option>
                @endforeach
            </select>
        </div>


        <!-- Email (cho phép nhập) -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <!-- Ngày sinh (cho phép nhập) -->
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Ngày sinh</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
        </div>

        <!-- Giới tính (cho phép chọn) -->
        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>

        <!-- Số điện thoại (cho phép nhập) -->
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>

        <!-- Địa chỉ (cho phép nhập) -->
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <!-- Hình ảnh đại diện (cho phép nhập) -->
        <div class="mb-3">
            <label for="profile_image" class="form-label">Hình ảnh đại diện</label>
            <input type="file" id="profile_image" name="profile_image" class="form-control">
        </div>

        <!-- Công ty (cho phép nhập) -->
        <div class="mb-3">
            <label for="company" class="form-label">Công ty</label>
            <input type="text" id="company" name="company" class="form-control">
        </div>

        <!-- Nút Lưu -->
        <button type="submit" class="btn btn-success">Lưu khách hàng</button>
        <!-- Nút Quay lại -->
        <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
