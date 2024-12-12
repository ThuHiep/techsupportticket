<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_create.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        .required {
            color: red;
            font-size: 14px;
        }
        .btn-secondary.btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            margin-left: 10px;
        }
        .is-invalid {
            border-color: red; /* Đổi màu biên ô nhập thành đỏ */
        }
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: left">Thêm khách hàng mới</h1>
    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-9">
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="customer_id" class="form-label">Mã khách hàng<span class="required">*</span></label>
                        <input type="text" id="customer_id" name="customer_id" class="form-control" value="{{ $randomId }}" readonly required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" required pattern="\d{1,9}" title="Mã số thuế chỉ được phép tối đa 9 chữ số">
                        <small id="tax-error" class="text-danger" style="display: none;">Vui lòng nhập mã số thuế!</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="full_name" class="form-label">Tên khách hàng<span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                        <small id="name-error" class="text-danger" style="display: none;">Vui lòng nhập tên khách hàng!</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" required pattern="\d{10}" title="Số điện thoại phải gồm 10 chữ số">
                        <small id="phone-error" class="text-danger" style="display: none;">Vui lòng nhập đúng số điện thoại!</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="email" class="form-label">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <small id="email-error" class="text-danger" style="display: none;">Email chưa đúng định dạng!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="software" class="form-label">Software<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" required>
                        <small id="software-error" class="text-danger" style="display: none;">Vui lòng nhập software!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control" required>
                        <small id="company-error" class="text-danger" style="display: none;">Vui lòng nhập công ty!</small>
                    </div>
                </div>
                <div class="row mb-3 address-website-container">
                    <div class="form-group col-6">
                        <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" required>
                        <small id="address-error" class="text-danger" style="display: none;">Vui lòng nhập địa chỉ!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" required>
                        <small id="website-error" class="text-danger" style="display: none;">Vui lòng nhập website!</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grouped-fields">
            <!-- Cột bên phải cho hình ảnh đại diện -->
                <div class="form-group row row1">
                    <div class="col-md-6 col-username">
                        <label for="username" class="form-label">Tài khoản<span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $username }}" readonly required>
                    </div>
                    <div class="col-md-6 col-password">
                        <label for="password" class="form-label">Mật khẩu<span class="required">*</span></label>
                        <input type="text" id="password" name="password" class="form-control" value="{{ $password }}" readonly required>
                        <button type="button" class="btn btn-secondary btn-sm" id="reset-password">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="container-img">
                    <div class="form-group">
                        <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                        <div class="custom-file-upload">
                            <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <label for="profile_image" class="custom-file-label">Chọn mới</label>
                            <div class="image-preview">
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img" src="" alt="Image Preview" style="display:none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-container">
            <button type="submit" class="btn btn-success me-3">Thêm mới</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
<script src="{{asset('admin/js/customer/script.js')}}"></script>
</body>
</html>
