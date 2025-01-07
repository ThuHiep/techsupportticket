<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin loại yêu cầu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/department/edit.css') }}">
    <!-- Có thể dùng luôn file CSS của create nếu style tương tự -->
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
<div class="container">
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    <h1>Sửa thông tin loại yêu cầu</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('requesttype.update', $requestType->request_type_id) }}" method="POST"> <!-- Sửa từ $requesttype thành $requestType -->
        @csrf
        @method('PUT')

        <div class="form-group-row">
            <div class="form-group">
                <label for="request_type_name">Tên loại yêu cầu:<span class="required">*</span></label> <!-- Đổi từ phòng ban thành loại yêu cầu -->
                <input type="text" name="request_type_name" id="request_type_name" value="{{ old('request_type_name', $requestType->request_type_name) }}" required> <!-- Sửa từ request_type_id thành request_type_name -->
                @error('request_type_name')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="add-department-btn">Cập nhật</button>
            <a href="{{ route('requesttype.index') }}" class="cancel-btn">Hủy</a>
        </div>
    </form>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
