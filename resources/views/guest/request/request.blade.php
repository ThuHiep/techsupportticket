<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gửi Yêu Cầu Hỗ Trợ</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('admin/css/form/guiyeucaukythuat.css') }}">
</head>
<body>
<div class="wrapper">
    <div class="support_box">
        <div class="support-header">
            <span>Gửi Yêu Cầu Hỗ Trợ</span>
        </div>

        <!-- Thời gian hiện tại -->
        <div class="date-time-container">
            <div class="date-time" id="current-datetime"></div>
        </div>

        <!-- Hiển thị thông báo -->
        @if(session('success'))
            <div style="color: green; text-align: center; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="color: red; text-align: center; margin-bottom: 10px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Hiển thị lỗi validate -->
        @if ($errors->any())
            <div style="color: red; text-align: center; margin-bottom: 10px;">
                <ul style="list-style:none;padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Input -->
        <form method="POST" action="{{ route('guest.request.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-container">
                <!-- Row 1 -->
                <div class="input_row">
                    <div class="input_box">
                        <input type="text" id="fullname" name="fullname" class="input-field" value="{{ $customer->full_name ?? old('fullname') }}" readonly>
                        <label for="fullname" class="label">Họ và tên</label>
                        <i class="bx bx-user icon"></i>
                    </div>
                    <div class="input_box">
                        <input type="tel" id="phone" name="phone" class="input-field" value="{{ $customer->phone ?? old('phone') }}" readonly>
                        <label for="phone" class="label">Số điện thoại</label>
                        <i class="bx bx-phone icon"></i>
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="input_box">
                    <input type="email" id="email" name="email" class="input-field" value="{{ $customer->email ?? old('email') }}" readonly>
                    <label for="email" class="label">Email</label>
                    <i class="bx bx-envelope icon"></i>
                </div>
                <!-- Row 3 -->
                <div class="input_row">
                    <div class="input_box">
                        <input type="text" id="company-name" name="company-name" class="input-field" value="{{ $customer->company ?? old('company-name') }}" readonly>
                        <label for="company-name" class="label">Tên công ty</label>
                        <i class="bx bx-buildings icon"></i>
                    </div>
                    <div class="input_box">
                        <select id="request-type" name="request-type" class="input-field" required>
                            <option value="" disabled {{ old('request-type') ? '' : 'selected' }}>Chọn loại yêu cầu</option>
                            @if(isset($requestTypes) && !$requestTypes->isEmpty())
                                @foreach($requestTypes as $type)
                                    <option value="{{ $type->request_type_id }}" {{ old('request-type') == $type->request_type_id ? 'selected' : '' }}>
                                        {{ $type->request_type_name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Không có loại yêu cầu khả dụng</option>
                            @endif
                        </select>
                        <label for="request-type" class="label">Loại yêu cầu</label>
                    </div>
                </div>

                <!-- Row 4 -->
                <!-- Thông tin yêu cầu -->
                <div class="request-info-row">
                    <span class="request-info-text">Thông tin yêu cầu</span>

                </div>

                <!-- request-container -->
                <div id="request-container">
                    <!-- Khối mặc định ban đầu -->
                    <div class="request-block">
                        <div class="input_box input-title-1">
                            <input type="text" name="title[]" class="input-field" required>
                            <label class="label">Tiêu đề</label>
                        </div>

                        <div class="input_box file-upload-box">
                            <label class="file-upload-label">Tải lên file hỗ trợ:</label>
                            <input type="file" name="attachments[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        </div>

                        <div class="input_box description-box">
                            <textarea name="description[]" class="input-field description-field" rows="10" required></textarea>
                            <label class="label">Mô tả vấn đề</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="input_box">
                <input type="submit" class="input-submit" value="Gửi Yêu Cầu">
            </div>
        </form>

        <!-- Return Link -->
        <div class="login-link">
            <span>Quay lại trang chủ? <a href="{{ route('homepage.index') }}" class="login-box">Bấm vào đây</a></span>
        </div>
    </div>
</div>

<!-- JavaScript hiển thị thời gian -->
<script>
    const dateTimeElement = document.getElementById("current-datetime");

    function updateDate() {
        const now = new Date();
        const formattedDate = now.toLocaleDateString("vi-VN", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        });
        dateTimeElement.textContent = `Ngày: ${formattedDate}`;
    }
    updateDate();
</script>
</body>
</html>
