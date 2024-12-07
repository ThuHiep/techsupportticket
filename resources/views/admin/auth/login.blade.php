<?php
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Boxicons -->
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/techsupportticket/public/admin/css/login_admin.css?v=1">
    <title>Login_Admin</title>
</head>
<!DOCTYPE html>
<html>
<body>
<div class="wrapper">
    <div class="logo_login_container">
        <div class="logo_box">
            <img src="/techsupportticket/public/admin/img/logosweetsoft.png" alt="Company Logo">
        </div>
        <div class="login_box">
            <div class="login-header">
                <span>Đăng nhập</span>
            </div>
            <form action="process_login.php" method="POST">
                <div class="input_box">
                    <input type="text" name="username" id="user" class="input-field" required>
                    <label for="user" class="label">Tên đăng nhập (email)</label>
                    <i class="bx bx-user icon"></i>
                </div>

                <div class="input_box">
                    <input type="password" name="password" id="pass" class="input-field" required>
                    <label for="pass" class="label">Mật khẩu</label>
                    <i class="bx bx-lock-alt icon"></i>
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ghi nhớ tài khoản</label>
                    </div>

                    <div class="forgot">
                        <a href="{{ route('forgot_pass_admin') }}">Quên mật khẩu?</a>
                    </div>
                </div>

                <!-- Captcha -->
                <div class="captcha_box">
                    <div class="g-recaptcha" data-sitekey="6Lcl14kqAAAAACOoIungM8WeSnOh9t7jIU2C_okM"></div>
                </div>

                <div class="input_box_submit">
                    <input type="submit" class="input-submit" value="Đăng nhập">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
