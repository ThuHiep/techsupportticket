
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="guest/css/form/register_user.css?v=1">
    <title>Register</title>

</head>
<body>
<div class="wrapper">
    <div class="register_box">
        <div class="register-header">
            <span>Đăng ký tài khoản</span>
        </div>
        <!--Input field-->
        <form action="process_register.php" method="POST">
            <div class="input-container">
                <!-- Cột trái -->
                <div class="column">
                    <div class="input_box">
                        <input type="text" id="fullname" name="fullname" class="input-field" required>
                        <label for="fullname" class="label">Họ và tên <span class="required">*</span></label>
                        <i class="bx bx-user icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="text" id="phone" name="phone" class="input-field" required>
                        <label for="phone" class="label">Số điện thoại <span class="required">*</span></label>
                        <i class="bx bx-phone icon"></i>
                    </div>

                    <div class="input_box">
                        <select id="gender" name="gender" class="input-field" required>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                        </select>
                        <label for="gender" class="label">Giới tính <span class="required">*</span></label>
                    </div>

                    <div class="input_box">
                        <input type="date" id="dob" name="dob" class="input-field" required>
                        <label for="dob" class="label">Ngày sinh <span class="required">*</span></label>
                    </div>

                    <div class="input_box">
                        <input type="text" id="address" name="address" class="input-field" required>
                        <label for="address" class="label">Địa chỉ <span class="required">*</span></label>
                        <i class="bx bx-map icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="text" id="company" name="company" class="input-field" required>
                        <label for="company" class="label">Tên công ty <span class="required">*</span></label>
                        <i class="bx bx-buildings icon"></i>
                    </div>
                </div>
                <!-- Cột phải -->
                <div class="column">
                    <div class="input_box">
                        <input type="email" id="email" name="email" class="input-field" required>
                        <label for="email" class="label">Email <span class="required">*</span></label>
                        <i class="bx bx-envelope icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="text" id="username" name="username" class="input-field" required>
                        <label for="username" class="label">Tên đăng nhập <span class="required">*</span></label>
                        <i class="bx bx-user-circle icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="password" id="password" name="password" class="input-field" required>
                        <label for="password" class="label">Mật khẩu <span class="required">*</span></label>
                        <i class="bx bx-lock-alt icon"></i>
                        <div class="password-hint">
                            <strong class="strong1">Gợi ý để tạo mật khẩu an toàn:</strong>
                            <div class="hint-list">
                                <ul>
                                    <li>Tối thiểu 8 ký tự</li>
                                    <li>1 số</li>
                                    <li>1 chữ in hoa</li>
                                    <li>1 ký tự đặc biệt</li>
                                    <li>1 chữ thường</li>
                                    <li>Ví dụ: @Aa123456</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="input_box">
                        <input type="password" id="password_confirm" name="password_confirm" class="input-field" required>
                        <label for="password_confirm" class="label">Xác nhận mật khẩu <span class="required">*</span></label>
                        <i class="bx bx-repeat icon"></i>
                    </div>
                </div>
            </div>
        </form>
        <!--Submit đăng ký-->
        <div class="input-box-submit">
            <input type="submit" class="input-submit" value="Đăng ký">
        </div>
        <!--Tro ve dang nhap-->
        <div class="login-link">
            <span>Đã có tài khoản? <a href="{{ route('login.login') }}" class="login-box">Đăng nhập ngay</a></span>
        </div>

    </div>
</div>
</body>
</html>
