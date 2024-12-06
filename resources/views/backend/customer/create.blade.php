<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
    <link rel="stylesheet" href="{{ asset('backend/css/customer/style.css') }}">
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
        /* Tổng quan */
        body {
            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc; /* Nền sáng */
        }

        /* Container chính */
        .container {
            max-width: 1200px;
            margin: 20px auto; /* Căn giữa */
            padding: 20px;
            background-color: #fff; /* Nền trắng */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
            border-radius: 12px; /* Bo góc mềm mại */
            position: relative; /* Thêm thuộc tính position: relative */
        }

        /* Tiêu đề */
        h1 {
            color: #FF9700; /* Màu cam nổi bật */
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form */
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Khoảng cách giữa các form-group */
            align-items: flex-start; /* Căn chỉnh các phần tử bắt đầu từ trên cùng */
        }


        .form-group {
            flex: 1;
            min-width: 300px;
            max-width: 48%;
        }

        .form-group label {
            font-weight: bold;
            font-size: 14px; /* Giảm kích thước font */
            margin-bottom: 6px; /* Khoảng cách ngắn hơn với input */
            display: block; /* Hiển thị label dạng khối */
            line-height: 1.2; /* Giảm khoảng cách dòng */
        }

        /* Input và Select */
        .form-group input,
        .form-group select {
            width: 100%; /* Chiều rộng full */
            height: 40px; /* Tăng chiều cao của ô nhập */
            padding: 6px; /* Giảm padding */
            font-size: 14px; /* Đặt font nhỏ hơn để đồng bộ */
            border: 2px solid #ddd; /* Viền xám nhạt */
            border-radius: 8px; /* Bo góc */
            transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Hiệu ứng focus */
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #FF9700; /* Đổi màu viền */
            box-shadow: 0 0 6px rgba(255, 151, 0, 0.4); /* Ánh sáng viền */
            outline: none; /* Bỏ đường outline */
        }

        /* Hiển thị lỗi */
        input.error, select.error {
            border-color: #dc3545; /* Màu đỏ */
            box-shadow: 0 0 6px rgba(220, 53, 69, 0.4); /* Ánh sáng đỏ */
        }

        .error-message {
            color: #dc3545; /* Màu chữ đỏ */
            font-size: 14px; /* Cỡ chữ nhỏ */
            margin-top: -10px; /* Dịch lên gần input */
            margin-bottom: 10px;
        }

        /* Nút */
        button {
            padding: 12px 24px; /* Khoảng cách trong lớn hơn */
            background-color: #50138d; /* Màu xanh lá */
            color: white;
            border: none;
            border-radius: 8px; /* Bo góc */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Hiệu ứng hover */
        }

        button:hover {
            background-color: #45a049; /* Xanh đậm hơn */
            transform: scale(1.05); /* Phóng to nhẹ */
        }

        button:active {
            transform: scale(0.95); /* Nhỏ đi khi click */
        }

        /* Nút Quay lại */
        .btn-secondary {
            position: absolute; /* Định vị nút Quay lại */
            top: 20px; /* Khoảng cách từ trên */
            right: 20px; /* Khoảng cách từ bên phải */
            padding: 12px 24px;
            background-color: #ff6f00;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-secondary:hover {
            background-color: #ee5711;
        }
        button, .btn-secondary {
            align-self: flex-start; /* Căn chỉnh các nút theo chiều dọc bắt đầu từ đầu */
            height: 50px; /* Đặt chiều cao cố định cho các nút */
        }
        /* Tùy chỉnh cho file upload */
        .custom-file-upload {
            position: relative;
            display: inline-block;
            width: 30%;
        }

        .custom-file-upload input[type="file"] {
            display: none; /* Ẩn input file gốc */
        }

        .custom-file-upload label {
            display: inline-block;
            width: 100%;
            padding: 12px 20px;
            background-color: #50138d;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .custom-file-upload label:hover {
            background-color: #6f2f9f;
        }

        /* Hiển thị ảnh preview */
        .image-preview {
            margin-top: 15px;
            text-align: center;
        }


        .image-preview img {
            max-width: 500px;
            max-height: 500px;
            border-radius: 8px;
            object-fit: cover;
            display: block; /* Đảm bảo ảnh luôn hiển thị */
            margin-bottom: 20px; /* Giữ khoảng cách giữa ảnh và các phần tử khác */
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 style="text-align: left">Thêm khách hàng mới</h1>
    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="customer_id" class="form-label">Mã khách hàng</label>
            <input type="text" id="customer_id" name="customer_id" class="form-control" value="{{ $randomId }}" readonly required>
        </div>

        <div class="form-group">
            <label for="tax_id" class="form-label">Mã số thuế</label>
            <input type="text" id="tax_id" name="tax_id" class="form-control" value="{{ $taxId }}" readonly required>
        </div>

        <div class="form-group">
            <label for="full_name" class="form-label">Tên khách hàng</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="user_id" class="form-label">Chọn khách hàng</label>
            <select id="user_id" name="user_id" class="form-control" required>
                <option value="" disabled selected>Chọn tên khách hàng</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->user_id }}">{{ $customer->user->username }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="website" class="form-label">Website</label>
            <input type="text" id="website" name="website" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="software" class="form-label">Software</label>
            <input type="text" id="software" name="software" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="date_of_birth" class="form-label">Ngày sinh</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gender" class="form-label">Giới tính</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="company" class="form-label">Công ty</label>
            <input type="text" id="company" name="company" class="form-control">
        </div>

        <div class="form-group">
            <label for="profile_image" class="form-label">Hình ảnh đại diện</label>
            <div class="custom-file-upload">
                <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                <label for="profile_image" class="custom-file-label">Chọn hình ảnh</label>
                <div id="image-preview" class="image-preview">
                    <img id="preview-img" src="" alt="Image Preview" style="display:none;">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Lưu khách hàng</button>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>
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
</body>
</html>
