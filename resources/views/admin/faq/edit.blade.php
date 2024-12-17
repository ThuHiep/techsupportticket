<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin FAQ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/faq/edit.css') }}">
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
    <h1>Sửa thông tin câu hỏi</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('faq.update', $faq->faq_id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Dòng chứa các trường chính -->
        <div class="form-group-row">
            <!-- Mã FAQ -->
            <div class="form-group  half-width">
                <label for="faq_id">Mã câu hỏi:<span class="required">*</span></label>
                <input type="text" name="faq_id" id="faq_id" value="{{ $faq->faq_id }}" readonly>
                @error('faq_id')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group  half-width">
                <label for="email">Email người gửi:<span class="required">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $faq->email) }}" required>
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Họ và tên -->
            
        </div>

        <!-- Dòng chứa Câu hỏi và Câu trả lời -->
        <!-- Dòng chứa Câu hỏi và Câu trả lời -->
        <div class="form-group-row">
            <!-- Câu hỏi -->
            <div class="form-group half-width">
                <label for="question">Câu hỏi:<span class="required">*</span></label>
                <textarea name="question" id="question" rows="3" required>{{ old('question', $faq->question) }}</textarea>
                @error('question')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Câu trả lời -->
            <div class="form-group half-width">
                <label for="answer">Câu trả lời:</label>
                <textarea name="answer" id="answer" rows="3">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <!-- Dòng chứa Nhân viên và Trạng thái -->
        <div class="form-group-row">
            <!-- Nhân viên trả lời -->
            
            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status">Trạng thái:<span class="required">*</span></label>
                <select name="status" id="status">
                    <option value="Chưa phản hồi" {{ old('status', $faq->status) == 'Chưa phản hồi' ? 'selected' : '' }}>Chưa phản hồi</option>
                    <option value="Đã phản hồi" {{ old('status', $faq->status) == 'Đã phản hồi' ? 'selected' : '' }}>Đã phản hồi</option>
                </select>
                @error('status')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Các nút hành động -->
        <div class="button-group">
            <button type="submit" class="add-faq-btn">Cập nhật</button>
            <a href="{{ route('faq.index') }}" class="cancel-btn">Hủy</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const answerField = document.getElementById("answer");
        const statusField = document.getElementById("status").closest(".form-group");

        if (answerField && statusField) {
            if (answerField.value.trim() !== "") {
                statusField.style.display = "none"; // Ẩn trường trạng thái
            }

            answerField.addEventListener("input", function () {
                if (answerField.value.trim() !== "") {
                    statusField.style.display = "none";
                } else {
                    statusField.style.display = "block";
                }
            });
        }
    });
</script>

</body>
</html>
