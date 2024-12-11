<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_create.css') }}">
    <!--<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #FF9700;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 300px;
            max-width: 48%;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #FF9700;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            padding: 10px 20px;
            background-color: #ccc;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-secondary:hover {
            background-color: #aaa;
        }

    </style> --> <!--ẨN STYLE GỐC-->
    <style>
        /* Khi sidebar ở trạng thái bình thường */
        body .container {
            width: calc(98%); /* Độ rộng sau khi trừ sidebar */
            transition: all 0.3s ease-in-out;
        }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%); /* Mở rộng nội dung khi sidebar thu nhỏ */
            transition: all 0.3s ease-in-out;
        }
        .required {
            color: red; /* Màu đỏ cho dấu sao */
            font-size: 14px; /* Kích thước của dấu sao */
        }
    </style>

</head>
<body>
<div class="container">
    <h1 style="text-align: left">Thêm khách hàng mới</h1>
    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Cột bên trái (3/4) và cột bên phải (1/4) -->
        <div class="row mb-3">
            <!-- Cột bên trái -->
            <div class="col-md-9">
                <!-- Mã KH + Mã số thuế + Chọn KH -->
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="customer_id" class="form-label">Mã khách hàng<span class="required">*</span></label>
                        <input type="text" id="customer_id" name="customer_id" class="form-control" value="{{ $randomId }}" readonly required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="full_name" class="form-label">Tên khách hàng<span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                    </div>
                </div>
                <!-- Ngày sinh + giới tính + số điện thoại -->
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
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                <!-- Software + Công ty -->
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="email" class="form-label">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="software" class="form-label">Software<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control">
                    </div>

                </div>
                <!-- Địa chỉ + Website -->
                <div class="row mb-3 address-website-container ">
                    <div class="form-group col-6">
                        <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    <div class="form-group col-6">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" required>
                    </div>
                </div>
            </div>


            <!-- Cột bên phải cho hình ảnh đại diện -->
            <div class="col-md-3 grouped-fields">
                <div class="form-group col-md-6">
                    <label for="username" class="form-label">Tài khoản<span class="required">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ $username }}" readonly required>
                </div>

                <div class="form-group col-md-6">
                    <label for="password" class="form-label">Mật khẩu<span class="required">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" value="{{ $password }}" readonly required>
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
        <!-- Nút hành động -->
        <div class="button-container">
            <button type="submit" class="btn btn-success me-3">Thêm mới</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-img');
            output.src = reader.result;
            output.style.display = 'block'; /* Hiển thị hình ảnh đã chọn */
        };
        reader.readAsDataURL(event.target.files[0]); /* Đọc file ảnh */
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.querySelector('.navbar-minimalize');
        if (toggleButton) {
            toggleButton.addEventListener('click', function () {
                document.body.classList.toggle('mini-navbar');
                console.log('Body class list:', document.body.className); // Debug xem lớp mini-navbar có được thêm
            });
        }
    });
</script>
<script>
    // Cảnh báo ngưười dùng
    document.getElementById('tax_id').addEventListener('input', function (event) {
        this.value = this.value.replace(/[^0-9]/g, ''); // Loại bỏ ký tự không phải số
        if (this.value.length > 9) {
            alert('Mã số thuế chỉ được phép tối đa 9 số!');
        }
    });
    document.getElementById('phone').addEventListener('input', function (event) {
        // Loại bỏ ký tự không phải số
        this.value = this.value.replace(/[^0-9]/g, '');

        // Kiểm tra nếu độ dài số điện thoại vượt quá 10 ký tự
        if (this.value.length > 10) {
            alert('Số điện thoại chỉ được phép tối đa 10 chữ số!');
        }
    });
</script>
</body>

</html>
