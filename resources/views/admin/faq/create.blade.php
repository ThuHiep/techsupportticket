<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới FAQ</title>
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
    </style>
</head>
<body>
<div class="container">
    <h1>Thêm mới FAQ</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('faq.store') }}" method="POST">
        @csrf

        <div class="form-group-row">
            <!-- Mã FAQ -->
            <div class="form-group">
                <label for="faq_id">Mã câu hỏi:<span class="required">*</span></label>
                <input
                    type="text"
                    name="faq_id"
                    id="faq_id"
                    value="{{ $nextId ?? '' }}"
                    readonly
                >
                @error('faq_id')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>
        
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
            <div class="form-group">
                <label for="full_name">Họ và tên:</label>
                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    value="{{ old('full_name') }}"
                    placeholder="Nhập họ và tên (không bắt buộc)"
                >
                @error('full_name')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>
        
            <!-- Số điện thoại -->
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    value="{{ old('phone') }}"
                    placeholder="Nhập số điện thoại (không bắt buộc)"
                    pattern="^[0-9]{10,15}$"
                    title="Số điện thoại phải từ 10 đến 15 chữ số."
                >
                @error('phone')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>
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

</body>
</html>
