<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('admin/css/form/change_pass.css') }}">

    <title>Thay đổi mật khẩu</title>
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="reset-password-box">
            <div class="reset-password-header">
                <span>Đổi mật khẩu</span>
            </div>
            <!-- admin đổi mật khẩu-->
            <form action="{{ route('updatePass', $user_id) }}" method="POST" enctype="multipart/form-data" class="reset-password-form">
                @csrf
                @method('PUT')
                <div class="input_box">
                    <input type="password" name="new-password" id="new-password" class="input-field" required>
                    <label for="new-password" class="label">Mật khẩu mới</label>
                    <i class="bx bx-lock icon"></i>
                </div>

                <div class="input_box">
                    <input type="password" name="confirm-password" id="confirm-password" class="input-field" required>
                    <label for="confirm-password" class="label">Xác nhận mật khẩu</label>
                    <i class='bx bx-repeat icon'></i>
                    @if ($errors->has('confirm-password'))
                    <div class="error-message">{{ $errors->first('confirm-password') }}</div>
                    @endif
                </div>

                <div class="input_box">
                    <input type="submit" class="input-submit" value="Cập nhật mật khẩu">
                </div>
            </form>
        </div>
    </div>
</body>

</html>