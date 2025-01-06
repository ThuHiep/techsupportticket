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

        .resend-button {
            margin-top: 10px;
            cursor: pointer;
            padding: 0;
        }

        .resend-button:disabled {
            cursor: not-allowed;
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
                    <button id="resendButton" class="resend-button input-submit" disabled>Gửi lại mã</button>
                </div>
            </form>
            <div class="back-to-login">
                <a href="{{ route('login') }}" class="login-box">Quay lại trang đăng nhập</a>
            </div>
        </div>
    </div>

</body>
<script>
    let countdown = localStorage.getItem('otpCountdown') !== null ?
        parseInt(localStorage.getItem('otpCountdown')) :
        300; // 5 minutes in seconds

    let resendCooldown = localStorage.getItem('resendCooldown') !== null ?
        parseInt(localStorage.getItem('resendCooldown')) :
        30; // 30 seconds in seconds

    if (countdown <= 0) {
        countdown = 300; // Reset to 5 minutes
        localStorage.removeItem('otpCountdown');
    }

    if (resendCooldown <= 0) {
        resendCooldown = 30; // Reset to 30 seconds
        localStorage.removeItem('resendCooldown');
    }

    const timerElement = document.createElement('div');
    timerElement.style.color = 'red';
    timerElement.style.marginTop = '5px';
    timerElement.style.marginLeft = '20px';
    timerElement.style.fontSize = '16px';

    const resendButton = document.getElementById('resendButton');
    const submitButton = document.querySelector('input[type="submit"]');
    submitButton.parentNode.insertBefore(timerElement, submitButton);

    const updateResendButton = () => {
        if (resendCooldown <= 0) {
            resendButton.disabled = false;
            resendButton.textContent = 'Gửi lại mã';
        } else {
            resendButton.disabled = true;
            resendButton.textContent = `Gửi lại mã sau ${resendCooldown}s`;
        }
    };

    const resendInterval = setInterval(() => {
        if (resendCooldown > 0) {
            resendCooldown--;
            localStorage.setItem('resendCooldown', resendCooldown);
        }
        updateResendButton();
    }, 1000);

    const otpInterval = setInterval(() => {
        if (countdown <= 0) {
            clearInterval(otpInterval);
            localStorage.setItem('otpCountdown', 0);
            timerElement.textContent = 'Mã OTP đã hết hiệu lực.';
            submitButton.disabled = true;

            fetch("{{ route('deleteOtp', $user_id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: '{{ $user_id }}'
                })
            });
        } else {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;
            timerElement.textContent = `Mã OTP còn hiệu lực: ${minutes} phút ${seconds} giây`;
            countdown--;
            localStorage.setItem('otpCountdown', countdown);
        }
    }, 1000);

    resendButton.addEventListener('click', () => {
        // Reset countdowns
        countdown = 300;
        resendCooldown = 30;
        localStorage.setItem('otpCountdown', countdown);
        localStorage.setItem('resendCooldown', resendCooldown);

        // Update UI
        timerElement.textContent = `Mã OTP còn hiệu lực: 5 phút 0 giây`;
        updateResendButton();

        // Gửi yêu cầu gửi lại mã OTP
        fetch("{{ route('resendOtp', $user_id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: '{{ $user_id }}'
            })
        }).then(response => {
            if (response.ok) {
                alert('Mã OTP đã được gửi lại.');
            }
        }).catch(error => {
            console.error('Lỗi khi gửi lại OTP:', error);
        });
    });

    updateResendButton();
</script>

</html>