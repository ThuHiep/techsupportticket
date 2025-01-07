<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới câu hỏi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/faq/create.css') }}">
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
    <h1>Thêm mới câu hỏi</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('faq.store') }}" method="POST">
        @csrf
        <input type="hidden" name="faq_id" value="{{ $nextId }}">

        <div class="form-group-row">
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email người gửi:<span class="required">*</span></label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>

            <!-- Họ và tên -->

        </div>
        <div class="form-group-row">
            <!-- Câu hỏi -->
            <div class="form-group">
                <label for="question">Câu hỏi:<span class="required">*</span></label>
                <textarea
                    name="question"
                    id="question"
                    rows="3"
                    required
                >{{ old('question') }}</textarea>
                @error('question')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>

            <!-- Câu trả lời -->
            <div class="form-group">
                <label for="answer">Câu trả lời:</label>
                <textarea
                    name="answer"
                    id="answer"
                    rows="3"
                >{{ old('answer') }}</textarea>
                @error('answer')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Input ẩn để lưu trạng thái -->
        <input type="hidden" name="status" id="status" value="Chưa phản hồi">

        <div class="button-group">
            <button type="submit" class="add-faq-btn">Thêm mới</button>
            <a href="{{ route('faq.index') }}" class="cancel-btn">Quay lại</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const answerField = document.getElementById('answer');
        const statusField = document.getElementById('status');

        // Theo dõi sự thay đổi nội dung trong trường "Câu trả lời"
        answerField.addEventListener('input', function () {
            if (answerField.value.trim() !== '') {
                statusField.value = 'Đã phản hồi';
            } else {
                statusField.value = 'Chưa phản hồi';
            }
        });
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
