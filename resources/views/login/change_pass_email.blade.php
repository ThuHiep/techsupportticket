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
            <form action="{{ route('updatePassEmail', $user_id) }}" method="POST" enctype="multipart/form-data" class="reset-password-form">
                @csrf
                @method('PUT')

                <div class="input_box">
                    <input type="password" name="old-password" id="old-password" class="input-field" value="{{ old('old-password') }}" required>
                    <label for="old-password" class="label">Mật khẩu cũ</label>
                    <i class="bx bx-hide toggle-password" onclick="togglePassword('old-password', this)"></i>
                    @if ($errors->has('old-password'))
                    <div class="error-message">{{ $errors->first('old-password') }}</div>
                    @endif
                </div>

                <div class="input_box">
                    <input type="password" name="new-password" id="new-password" class="input-field" value="{{ old('new-password') }}" required>
                    <label for="new-password" class="label">Mật khẩu mới</label>
                    <i class="bx bx-hide toggle-password" onclick="togglePassword('new-password', this)"></i>
                    <span class="error-message" id="password_error"></span>
                </div>

                <div class="input_box">
                    <input type="password" name="confirm-password" id="confirm-password" class="input-field" value="{{ old('confirm-password') }}" required>
                    <label for="confirm-password" class="label">Xác nhận mật khẩu</label>
                    <i class="bx bx-hide toggle-password" onclick="togglePassword('confirm-password', this)"></i>
                    <span class="error-message" id="password_confirm_error"></span>
                </div>

                <div class="input_box">
                    <input type="submit" class="input-submit" value="Cập nhật mật khẩu">
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = "password";
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('new-password');
        const passwordConfirmInput = document.getElementById('confirm-password');
        const form = document.querySelector('form');

        // Kiểm tra mật khẩu
        passwordInput.addEventListener('input', function() {
            const errorMessage = document.getElementById('password_error');
            const passwordValue = passwordInput.value;

            // Kiểm tra mật khẩu
            if (passwordValue.length < 8) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất 8 ký tự.';
            } else if (!/[A-Z]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một chữ cái viết hoa.';
            } else if (!/[0-9]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một số.';
            } else if (!/[!@#$%^&*]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một ký tự đặc biệt.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Xử lý khi nhấn "Đăng ký"
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let hasError = false;

            // Password validation
            if (passwordInput.value.length < 8 || !/[A-Z]/.test(passwordInput.value) || !/[0-9]/.test(passwordInput.value) || !/[!@#$%^&*]/.test(passwordInput.value)) {
                document.getElementById('password_error').textContent = 'Mật khẩu yếu: phải chứa ít nhất 8 ký tự bao gồm chữ in hoa, số và ký tự đặc biệt.';
                hasError = true;
            }

            // Password confirmation validation
            if (passwordInput.value !== passwordConfirmInput.value) {
                document.getElementById('password_confirm_error').textContent = 'Mật khẩu xác nhận không khớp.';
                hasError = true;
            }

            // Nếu không có lỗi thì gửi form
            if (!hasError) {
                form.submit();
            }
        });
    });
</script>

</html>