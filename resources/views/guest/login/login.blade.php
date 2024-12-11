<?php
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="guest/css/form/login_user.css?v=1">
    <title>Login_User</title>
</head>
<body>
<div class="wrapper">
    <div class="logo_box">
        <img src="guest/img/logosweetsoft.png" alt="Company Logo">
    </div>
    <div class="login_box">
        <div class="login-header">
            <span>Đăng nhập</span>
        </div>

        <form action="process_login.php" method="POST">
            <div class="input_box">
                <input type="text" id="user" name="username" class="input-field" required>
                <label for="user" class="label">Tên đăng nhập (email)</label>
                <i class="bx bx-user icon"></i>
            </div>

            <div class="input_box">
                <input type="password" id="pass" name="password" class="input-field" required>
                <label for="pass" class="label">Mật khẩu</label>
                <i class="bx bx-lock-alt icon"></i>
            </div>

            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ tài khoản</label>
                </div>

                <div class="forgot">
                    <a href="{{ route('forgot_pass') }}" > Quên mật khẩu</a>
                </div>
            </div>

            <div class="input_box_submit">
                <input type="submit" class="input-submit" value="Đăng nhập">
            </div>
        </form>

        <div class="register">
            <span>Bạn chưa có tài khoản?<a href="{{ route('register') }}" class="register-box"> Đăng ký ngay</a></span>
        </div>
    </div>
</div>
</body>
</html>
