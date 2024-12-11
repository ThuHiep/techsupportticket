<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="guest/css/form/forgot_password.css?v=1">
    <title>Quên mật khẩu</title>
</head>
<body>
<div class="wrapper">
    <div class="forgot_box">
        <div class="forgot-header">
            <span>Quên mật khẩu</span>
        </div>
        <div class="input_box">
            <input type="email" id="email" class="input-field" name="email" required>
            <label for="email" class="label">Nhập email đã đăng ký</label>
            <i class="bx bx-envelope icon"></i>
        </div>
        <div class="input_box">
            <input type="submit" class="input-submit" value="Gửi liên kết khôi phục qua mail">
        </div>
        <div class="back-to-login">
            <span>Bạn đã nhớ lại mật khẩu? <a href="{{ route('login.login') }}" class="login-box">Đăng nhập</a></span>
        </div>
    </div>
</div>
<!--JavaScript chuyen huong den thong bao "lien ket khoi phuc da gui"-->
<script>
    document.querySelector('.input-submit').addEventListener('click', function(e) {
        e.preventDefault(); // Ngăn form submit mặc định
        window.location.href = "thongbaodaguimail.blade.php"; // Chuyển hướng sang trang thông báo
    });
</script>
</body>
</html>
