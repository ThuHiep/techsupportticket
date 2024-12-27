<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_edit.css') }}">
    <title>Chỉnh sửa khách hàng</title>
    <style>
        /* Khi sidebar ở trạng thái bình thường */
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }

        .required {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
<div class="container">
    <h1 style="text-align: left">Chỉnh sửa thông tin khách hàng</h1>

    <form action="{{ route('customer.update', $customers->customer_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Cột bên trái (3/4) và cột bên phải (1/4) -->
        <div class="row mb-3">
            <!-- Cột bên trái -->
            <div class="col-md-9">
                <!-- Mã KH + Mã số thuế + Tên KH -->
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="customer_id" class="form-label">Mã khách hàng<span class="required">*</span></label>
                        <input type="text" id="customer_id" name="customer_id" class="form-control" value="{{ $customers->customer_id }}" readonly required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="username" class="form-label">Tên tài khoản<span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $customers->user->username }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="full_name" class="form-label">Tên khách hàng<span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ $customers->full_name }}" required>
                    </div>
                </div>

                <!-- Ngày sinh + giới tính + số điện thoại -->
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ $customers->date_of_birth->toDateString() }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Nam" {{ $customers->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ $customers->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $customers->phone }}" required>
                    </div>
                </div>

                <!-- Email + Software + Công ty -->
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control" value="{{ $customers->company }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" value="{{ $customers->tax_id }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="software" class="form-label">Phần mềm<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" value="{{ $customers->software }}">
                    </div>

                </div>

                <!-- Địa chỉ + Website -->
                <div class="row mb-3 address-website-container">
                    <div class="form-group col-6">
                        <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ $customers->address }}" required>
                    </div>
                    <div class="form-group col-6">
                        <label for="email" class="form-label">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $customers->email }}">
                    </div>
                    <div class="form-group col-6">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" value="{{ $customers->website }}">
                    </div>
                </div>
            </div>

            <!-- Cột bên phải cho hình ảnh đại diện -->
            <div class="col-md-3">
                <div class="container-img">
                    <div class="form-group">
                        <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                        <div class="custom-file-upload">
                            <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <label for="profile_image" class="custom-file-label">Chọn khác</label>
                            <div class="image-preview">
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img"
                                         src="{{ $customers->profile_image ? asset('admin/img/customer/' . $customers->profile_image) : asset('admin/img/customer/default.png') }}"
                                         alt="Hình đại diện">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="button-container">
            <button type="submit" class="btn btn-success me-3">Cập nhật</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Hủy</a>
        </div>

    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('admin/js/customer/script.js')}}"></script>
</div>
</body>

</html>
