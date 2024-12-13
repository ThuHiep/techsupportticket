<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/techsupportticket/public/admin/css/form/forgot_password.css?v=1">
    <title>Quên mật khẩu</title>
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
                <span>Quên mật khẩu</span>
            </div>
            <form action="{{route('forgotPassProcess')}}" method="POST">
                @csrf
                <div class="input_box">
                    <input type="email" id="email" class="input-field" name="email" required>
                    <label for="email" class="label">Nhập email đã đăng ký</label>
                    <i class="bx bx-envelope icon"></i>
                    @if (session('error'))
                    <div class="error-message" style="color: red;">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
                <div class="input_box">
                    <input type="submit" class="input-submit" value="Gửi mã xác thực">
                </div>
            </form>
            <div class="back-to-login">
                <a href="{{ route('login') }}" class="login-box">Quay lại trang đăng nhập</a>
            </div>
        </div>
    </div>

</body>

</html>