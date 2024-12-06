
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/techsupportticket/public/backend/css/change_pass.css?v=1">
    <title>Thay đổi mật khẩu</title>

</head>

<body>
<div class="wrapper">
    <div class="reset-password-box">
        <div class="reset-password-header">
            <span>Đổi mật khẩu</span>
        </div>
        <!--Backend đổi mật khẩu-->
        <form action="process_change_password.php" method="POST" class="reset-password-form">
            <div class="input_box">
                <input type="password" name="new-password" id="new-password" class="input-field" required>
                <label for="new-password" class="label">Mật khẩu mới</label>
                <i class="bx bx-lock icon"></i>
            </div>
            <div class="input_box">
                <input type="password" name="confirm-password" id="confirm-password" class="input-field" required>
                <label for="confirm-password" class="label">Xác nhận mật khẩu</label>
                <i class='bx bx-repeat icon'></i>
            </div>
            <div class="input_box">
                <input type="submit" class="input-submit" value="Cập nhật mật khẩu">
            </div>
        </form>
    </div>
</div>
</body>
</html>
