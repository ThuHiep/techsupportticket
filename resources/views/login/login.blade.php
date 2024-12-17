
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('admin/css/form/login_admin.css') }}">
    <title>Login_Admin</title>
</head>
<!DOCTYPE html>
<body>
    <div class="wrapper">
        <div class="logo_login_container">
            <div class="logo_box">
                <img src="admin/img/logosweetsoft.png" alt="Company Logo">
            </div>
            <div class="login_box">
                <div class="login-header">
                    <span>Đăng nhập</span>
                </div>
                <form action="{{route('loginProcess')}}" method="POST">
                    @csrf
                    <div class="input_box">
                        <input type="text" name="username" id="user" class="input-field" required>
                        <label for="user" class="label">Tên đăng nhập</label>
                        <i class="bx bx-user icon"></i>
                    </div>

                    <!-- Trường Mật khẩu cũ được thay thế bởi input mới -->
                    <div class="input_box">
                        <input type="password" name="password" class="input-field" id="password" required>
                        <label class="label" for="password">Mật khẩu</label>
                        <span class="icon" id="togglePassword">
                            <i class="fa fa-eye-slash"></i> <!-- Mắt nhắm mặc định -->
                        </span>
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ghi nhớ tài khoản</label>
                        </div>

                        <div class="forgot">
                            <a href="{{ route('forgotPass') }}">Quên mật khẩu?</a>
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
                <div class="register">
                    <span>Bạn chưa có tài khoản?<a href="{{ route('register') }}" class="register-box"> Đăng ký ngay</a></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tìm phần tử và thêm sự kiện click vào biểu tượng mắt
        const togglePassword = document.getElementById("togglePassword");
        const passwordField = document.getElementById("password"); // Sử dụng đúng id "pass"

        togglePassword.addEventListener("click", function () {
            // Kiểm tra nếu mật khẩu đang bị ẩn, chuyển sang hiển thị
            if (passwordField.type === "password") {
                passwordField.type = "text"; // Đổi loại input thành 'text' để hiển thị mật khẩu
                togglePassword.innerHTML = '<i class="fa fa-eye"></i>'; // Thay đổi biểu tượng thành mắt mở
            } else {
                passwordField.type = "password"; // Đổi lại loại input thành 'password' để ẩn mật khẩu
                togglePassword.innerHTML = '<i class="fa fa-eye-slash"></i>'; // Thay đổi biểu tượng thành mắt nhắm
            }
        });


    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>

