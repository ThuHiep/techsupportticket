<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới loại yêu cầu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/department/create.css') }}">
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none; /* Mặc định ẩn */
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            border: 8px solid #f3f3f3; /* Màu nền */
            border-top: 8px solid #3498db; /* Màu xoay */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>
<div class="container">
    <h1>Thêm mới phòng ban</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('requesttype.store') }}" method="POST">
        @csrf
        <input type="hidden" name="request_type_id" value="{{ $nextId }}">

        <!-- Row chứa các trường trên cùng 1 dòng -->
        <div class="form-group-row">
            <div class="form-group">
                <label for="request_type_name">Tên loại yêu cầu:<span class="required">*</span></label>
                <input
                    type="text"
                    name="request_type_name"
                    id="request_type_name"
                    value="{{ old('request_type_name') }}"
                    required
                    pattern="[A-Za-zÀ-ỹ\s]+"
                    title="Chỉ được chứa chữ cái và khoảng trắng."
                    aria-describedby="request_type_name_error"
                >
                <div id="request_type_name_error" class="error"></div>
                @error('request_type_name')
                <div class="error visible">{{ $message }}</div>
                @enderror
                <div id="request_type_name_error" class="error"></div>
            </div>

        </div>

        <div class="button-group">
            <button type="submit" class="add-department-btn">Thêm mới</button>
            <a href="{{ route('requesttype.index') }}" class="cancel-btn">Quay lại</a>
        </div>
    </form>
</div>

<!-- Thêm JavaScript để ngăn không nhập ký tự không hợp lệ và hiển thị thông báo lỗi -->
<script>
    document.getElementById('request_type_name').addEventListener('input', function (e) {
        const input = e.target;
        const value = input.value;
        const regex = /^[A-Za-zÀ-ỹ\s]+$/u;
        const errorDiv = document.getElementById('request_type_name_error');

        if (!regex.test(value)) {
            // Loại bỏ các ký tự không hợp lệ
            input.value = value.replace(/[^A-Za-zÀ-ỹ\s]/g, '');
            // Hiển thị thông báo lỗi
            errorDiv.textContent = 'Loại yêu cầu chỉ được chứa chữ cái và khoảng trắng.';
            errorDiv.classList.add('visible');
        } else {
            // Xóa thông báo lỗi nếu dữ liệu hợp lệ
            errorDiv.textContent = '';
            errorDiv.classList.remove('visible');
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');

        // Hàm hiển thị overlay loading
        function showLoading() {
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'flex'; // Hiển thị overlay
        }

        // Xử lý sự kiện submit form
        form.addEventListener('submit', function(e) {
            showLoading(); // Hiển thị overlay loading
        });
    });
</script>
</body>
</html>
