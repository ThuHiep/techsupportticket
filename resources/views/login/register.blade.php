<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boxicons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="guest/css/form/register_user.css?v=1">
    <title>Register</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top:62px; /* Khoảng cách phía trên lỗi */
            margin-bottom: 5px; /* Khoảng cách phía dưới lỗi */
            position: absolute; /* Vị trí tương đối */
            display: block;
            width: 100%; /* Đảm bảo lỗi không vượt khỏi khung */
            white-space: normal; /* Cho phép lỗi xuống dòng nếu quá dài */
            line-height: 1.2em; /* Tăng dòng nếu lỗi nhiều */
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="register_box">
        <div class="register-header">
            <span>Đăng ký tài khoản</span>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <!--Input field-->
        <form action="{{ route('registerProcess') }}" method="POST">
            @csrf
            <div class="input-container">
                <!-- Cột trái -->
                <div class="column">
                    <div class="input_box">
                        <input type="text" id="full_name" name="full_name" class="input-field" required>
                        <label for="full_name" class="label">Họ và tên <span class="required">*</span></label>
                        <i class="bx bx-user icon"></i>
                        <span class="error-message" id="full_name_error"></span>
                    </div>

                    <div class="input_box">
                        <input type="text" id="phone" name="phone" class="input-field" required>
                        <label for="phone" class="label">Số điện thoại <span class="required">*</span></label>
                        <i class="bx bx-phone icon"></i>
                        <span class="error-message" id="phone_error"></span>
                    </div>

                    <div class="sexual-date-row">
                        <div class="input_box">
                            <select id="gender" name="gender" class="input-field" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                            <label for="gender" class="label">Giới tính <span class="required">*</span></label>
                            <i class="bx bx-male-female icon"></i>
                        </div>

                        <div class="input_box">
                            <input type="date" id="date_of_birth" name="date_of_birth" class="input-field" required>
                            <label for="date_of_birth" class="label">Ngày sinh <span class="required">*</span></label>
                            <span class="error-message" id="date_of_birth_error"></span>
                        </div>
                    </div>

                    <div class="input_box">
                        <input type="text" id="address" name="address" class="input-field" required>
                        <label for="address" class="label">Địa chỉ <span class="required">*</span></label>
                        <i class="bx bx-map icon"></i>
                        <span class="error-message" id="address_error"></span>
                    </div>

                    <div class="input_box">
                        <input type="text" id="company" name="company" class="input-field" required>
                        <label for="company" class="label">Tên công ty <span class="required">*</span></label>
                        <i class="bx bx-buildings icon"></i>
                        <span class="error-message" id="company_error"></span>
                    </div>
                </div>
                <!-- Cột phải -->
                <div class="column">
                    <div class="input_box">
                        <input type="email" id="email" name="email" class="input-field" required>
                        <label for="email" class="label">Email <span class="required">*</span></label>
                        <span class="error-message" id="email_error"></span>
                        <i class="bx bx-envelope icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="text" id="username" name="username" class="input-field" required>
                        <label for="username" class="label">Tên đăng nhập <span class="required">*</span></label>
                        <span class="error-message" id="username_error"></span>
                        <i class="bx bx-user-circle icon"></i>
                    </div>

                    <div class="input_box">
                        <input type="password" id="password" name="password" class="input-field" required>
                        <label for="password" class="label">Mật khẩu <span class="required">*</span></label>
                        <span class="error-message" id="password_error"></span>

                        <i class="bx bx-show toggle-password icon" data-target="password"></i>
                        <div class="password-hint">
                            <strong class="strong1">Gợi ý để tạo mật khẩu an toàn:</strong>
                            <div class="hint-list">
                                <ul>
                                    <li class="hint" id="hint_length">Tối thiểu 8 ký tự</li>
                                    <li class="hint" id="hint_uppercase">1 chữ cái in hoa</li>
                                    <li class="hint" id="hint_number">1 số</li>
                                    <li class="hint" id="hint_special">1 ký tự đặc biệt</li>
                                    <li class="hint" id="hint_lowercase">1 chữ thường</li>
                                    <li class="hint" id="hint_example">Ví dụ: @Aa123456</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="input_box password_cf">
                        <input type="password" id="password_confirm" name="password_confirmation" class="input-field" required>
                        <label for="password_confirm" class="label">Xác nhận mật khẩu <span class="required">*</span></label>

                        <i class="bx bx-show toggle-password icon" data-target="password_confirm"></i>
                        <span class="error-message" id="password_confirm_error"></span>
                    </div>
                </div>
            </div>
            <!--Submit đăng ký-->
            <div class="input-box-submit">
                <input type="submit" class="input-submit" value="Đăng ký">
            </div>
        </form>
        <!--Tro ve dang nhap-->
        <div class="login-link">
            <span>Đã có tài khoản? <a href="{{ route('login') }}" class="login-box">Đăng nhập ngay</a></span>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fullNameInput = document.getElementById('full_name');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const companyInput = document.getElementById('company');
        const dateOfBirthInput = document.getElementById('date_of_birth');
        const emailInput = document.getElementById('email');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirm');
        const form = document.querySelector('form');

        // Kiểm tra họ và tên
        fullNameInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('full_name_error');
            if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(fullNameInput.value)) {
                errorMessage.textContent = 'Họ và tên không được chứa số hoặc ký tự đặc biệt.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Kiểm tra tên đăng nhập
        usernameInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('username_error');
            if (!/^[a-zA-Z0-9]+$/.test(usernameInput.value)) {
                errorMessage.textContent = 'Tên đăng nhập chỉ được chứa chữ cái và số, không chứa dấu câu.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Kiểm tra mật khẩu
        passwordInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('password_error');
            const passwordValue = passwordInput.value;

            // Reset màu sắc cho tất cả các gợi ý
            document.querySelectorAll('.hint').forEach(hint => {
                hint.style.color = 'black'; // Hoặc màu mặc định bạn muốn
            });

            // Kiểm tra các điều kiện
            if (passwordValue.length >= 8) {
                document.getElementById('hint_length').style.color = 'orange';
            }
            if (/[A-Z]/.test(passwordValue)) {
                document.getElementById('hint_uppercase').style.color = 'orange';
            }
            if (/[0-9]/.test(passwordValue)) {
                document.getElementById('hint_number').style.color = 'orange';
            }
            if (/[!@#$%^&*]/.test(passwordValue)) {
                document.getElementById('hint_special').style.color = 'orange';
            }
            if (/[a-z]/.test(passwordValue)) {
                document.getElementById('hint_lowercase').style.color = 'orange';
            }

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
// JavaScript để ẩn/hiện mật khẩu
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const targetId = icon.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                }
            });
        });
        // Kiểm tra số điện thoại
        phoneInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('phone_error');
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
            if (phoneInput.value.length > 10) {
                phoneInput.value = phoneInput.value.slice(0, 10);
                errorMessage.textContent = 'Số điện thoại chỉ được chứa tối đa 10 chữ số.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Kiểm tra ngày sinh
        dateOfBirthInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('date_of_birth_error');
            const dobValue = dateOfBirthInput.value;
            const currentDate = new Date();
            const [year, month, day] = dobValue.split('-').map(Number);

            if (!year || !month || !day) {
                errorMessage.textContent = 'Vui lòng nhập đầy đủ ngày, tháng, năm.';
                return;
            }

            if (year < 1900) {
                errorMessage.textContent = 'Năm sinh không hợp lệ (phải từ 1900 trở đi).';
                return;
            }

            const dobDate = new Date(dobValue);
            if (isNaN(dobDate.getTime())) {
                errorMessage.textContent = 'Vui lòng nhập ngày sinh hợp lệ.';
                return;
            }

            const age = currentDate.getFullYear() - dobDate.getFullYear();
            const monthDiff = currentDate.getMonth() - dobDate.getMonth();
            const dayDiff = currentDate.getDate() - dobDate.getDate();
            const isUnder18 = age < 18 || (age === 18 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)));

            if (isUnder18) {
                errorMessage.textContent = 'Bạn phải đủ 18 tuổi để đăng ký.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Kiểm tra địa chỉ
        addressInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('address_error');
            if (/^\d+$/.test(addressInput.value.trim())) {
                errorMessage.textContent = 'Địa chỉ không được chứa toàn là số.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Kiểm tra tên công ty
        companyInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('company_error');
            if (/^\d+$/.test(companyInput.value.trim())) {
                errorMessage.textContent = 'Tên công ty không được chứa toàn là số.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Hàm kiểm tra định dạng email
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Kiểm tra email
        emailInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('email_error');
            const emailValue = emailInput.value.trim();

            if (!emailValue) {
                errorMessage.textContent = 'Vui lòng nhập email.';
            } else if (!isValidEmail(emailValue)) {
                errorMessage.textContent = 'Định dạng email không hợp lệ. Vui lòng nhập lại.';
            } else {
                errorMessage.textContent = '';
            }
        });

        // Xử lý khi nhấn "Đăng ký"
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let hasError = false;

            // Clear all previous errors
            document.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
            });

            // Full name validation
            if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(fullNameInput.value.trim())) {
                document.getElementById('full_name_error').textContent = 'Họ và tên không được chứa số hoặc ký tự đặc biệt.';
                hasError = true;
            }

            // Phone validation
            if (!/^\d{10}$/.test(phoneInput.value.trim())) {
                document.getElementById('phone_error').textContent = 'Số điện thoại phải gồm 10 chữ số.';
                hasError = true;
            }

            // Date of birth validation
            const dobValue = dateOfBirthInput.value;
            const [year, month, day] = dobValue.split('-').map(Number);

            if (!year || !month || !day) {
                document.getElementById('date_of_birth_error').textContent = 'Vui lòng nhập đầy đủ ngày, tháng, năm.';
                hasError = true;
            } else if (year < 1900) {
                document.getElementById('date_of_birth_error').textContent = 'Năm sinh không hợp lệ (phải từ 1900 trở đi).';
                hasError = true;
            }

            // Address validation
            if (/^\d+$/.test(addressInput.value.trim())) {
                document.getElementById('address_error').textContent = 'Địa chỉ không được chứa toàn là số.';
                hasError = true;
            }

            // Company validation
            if (/^\d+$/.test(companyInput.value.trim())) {
                document.getElementById('company_error').textContent = 'Tên công ty không được chứa toàn là số.';
                hasError = true;
            }

            // Email validation
            if (!isValidEmail(emailInput.value.trim())) {
                document.getElementById('email_error').textContent = 'Email không hợp lệ.';
                hasError = true;
            }

            // Username validation
            if (!/^[a-zA-Z0-9]+$/.test(usernameInput.value.trim())) {
                document.getElementById('username_error').textContent = 'Tên đăng nhập chỉ được chứa chữ cái và số, không chứa dấu câu.';
                hasError = true;
            }

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
</body>
</html>
