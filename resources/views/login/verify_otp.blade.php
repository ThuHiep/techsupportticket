<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('admin/css/form/forgot_password.css') }}">
    <title>Xác thực mã</title>
    <style>
        .error-message {
            color: red;
            font-size: 16px;
            margin-top: 5px;
            margin-left: 20px;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="forgot_box">
        <div class="forgot-header">
            <span>Xác thực mã</span>
        </div>
        <form action="{{ route('verifyOTPProcess', $user_id) }}" method="POST">
            @csrf
            <div class="input_box">
                <input type="otp" id="otp" class="input-field" name="otp" required>
                <label for="otp" class="label">Mã xác nhận</label>
                @if (session('otp'))
                    <div class="error-message" style="color: red;">
                        {{ session('otp') }}
                    </div>
                @endif
            </div>
            <div class="input_box">
                <input type="submit" class="input-submit" value="Xác thực">
            </div>
        </form>
        <div class="back-to-login">
            <a href="{{ route('login') }}" class="login-box">Quay lại trang đăng nhập</a>
        </div>
    </div>
</div>

</body>
<script>
    let countdown = localStorage.getItem('otpCountdown') !== null
        ? parseInt(localStorage.getItem('otpCountdown'))
        : 300; // 5 minutes in seconds

    // Kiểm tra nếu countdown đã là 0
    if (countdown <= 0) {
        countdown = 300; // Đặt lại về 300 giây
        localStorage.removeItem('otpCountdown'); // Xóa countdown cũ
    }

    const timerElement = document.createElement('div');
    timerElement.style.color = 'red';
    timerElement.style.marginTop = '5px';
    timerElement.style.marginLeft = '20px';
    timerElement.style.fontSize = '16px';

    const submitButton = document.querySelector('input[type="submit"]');
    submitButton.parentNode.insertBefore(timerElement, submitButton);

    const interval = setInterval(() => {
        if (countdown <= 0) {
            clearInterval(interval);
            localStorage.setItem('otpCountdown', 0); // Đánh dấu OTP hết hạn
            timerElement.textContent = 'Mã OTP đã hết hiệu lực.';
            submitButton.disabled = true;

            // Gửi yêu cầu xóa OTP trong CSDL
            fetch('{{ route('deleteOtp', $user_id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: '{{ $user_id }}' })
            });
        } else {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;
            timerElement.textContent = `Mã OTP còn hiệu lực: ${minutes} phút ${seconds} giây`;
            countdown--;
            localStorage.setItem('otpCountdown', countdown); // Cập nhật countdown
        }
    }, 1000);

    // Ẩn bộ đếm nếu OTP đã hết hạn
    if (localStorage.getItem('otpCountdown') == 0) {
        timerElement.textContent = 'Mã OTP đã hết hiệu lực.';
        submitButton.disabled = true;
    }
</script>

</html>
