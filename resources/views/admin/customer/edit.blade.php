<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_edit.css') }}">
    <title>Chỉnh sửa khách hàng</title>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Chỉnh sửa thông tin khách hàng</h1>
    <form action="{{ route('customer.update', $customers->customer_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Grid container -->
        <div class="row">
            <!-- Cột trái -->
            <div class="col-md-6">
                <!-- Mã khách hàng -->
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Mã khách hàng</label>
                    <input type="text" id="customer_id" name="customer_id" class="form-control"
                           value="{{ $customers->customer_id }}" readonly>
                </div>

                <!-- Mã số thuế -->
                <div class="mb-3">
                    <label for="tax_id" class="form-label">Mã số thuế</label>
                    <input type="text" id="tax_id" name="tax_id" class="form-control"
                           value="{{ $customers->tax_id }}" required>
                </div>

                <!-- Tên khách hàng -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên khách hàng</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                           value="{{ $customers->full_name }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ $customers->email }}" required>
                </div>

                <!-- Ngày sinh -->
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Ngày sinh</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                           value="{{ $customers->date_of_birth }}" required>
                </div>

                <!-- Phần mềm -->
                <div class="mb-3">
                    <label for="software" class="form-label">Phần mềm</label>
                    <input type="text" id="software" name="software" class="form-control"
                           value="{{ $customers->software }}">
                </div>

                <!-- Website -->
                <div class="mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="text" id="website" name="website" class="form-control"
                           value="{{ $customers->website }}">
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-md-6">
                <!-- Giới tính -->
                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="{{ $customers->phone }}" required>
                </div>

                <!-- Địa chỉ -->
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" id="address" name="address" class="form-control"
                           value="{{ $customers->address }}" required>
                </div>

                <!-- Hình ảnh đại diện -->
                <div class="mb-3">
                    <label for="profile_image" class="form-label">Hình ảnh đại diện</label>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('admin/img/customer/' . $customers->profile_image) }}" alt="Hình đại diện"
                             class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <input type="file" id="profile_image" name="profile_image" class="form-control w-50">
                    </div>
                </div>

                <!-- Công ty -->
                <div class="mb-3">
                    <label for="company" class="form-label">Công ty</label>
                    <input type="text" id="company" name="company" class="form-control"
                           value="{{ $customers->company }}">
                </div>

            </div>
        </div>

        <!-- Nút hành động -->
        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-success me-3">Lưu thay đổi</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
