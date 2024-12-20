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
            <form action="{{route('verifyOTPProcess',$user_id)}}" method="POST">
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

</html>