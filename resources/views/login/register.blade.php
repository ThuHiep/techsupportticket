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
            margin-top: 5px;
            display: block;
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

                    <div class="input_box">
                        <select id="gender" name="gender" class="input-field" required>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                        <label for="gender" class="label">Giới tính <span class="required">*</span></label>
                    </div>

                    <div class="input_box">
                        <input type="date" id="date_of_birth" name="date_of_birth" class="input-field" required>
                        <label for="date_of_birth" class="label">Ngày sinh <span class="required">*</span></label>
                        <span class="error-message" id="date_of_birth_error"></span>
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
                        <input type="password" id="password_confirm" name="password_confirmation" class="input-field" required>
                        <label for="password_confirm" class="label">Xác nhận mật khẩu <span class="required">*</span></label>
                        <i class="bx bx-repeat icon"></i>
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
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fullNameInput = document.getElementById('full_name');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const companyInput = document.getElementById('company');
        const dateOfBirthInput = document.getElementById('date_of_birth');
        const emailInput = document.getElementById('email');
        const usernameInput = document.getElementById('username');
        const form = document.querySelector('form');
        // Kiểm tra họ và tên (thời gian thực)
        fullNameInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('full_name_error');
            if (!/^[a-zA-ZÀ-ỹ\s]*$/.test(fullNameInput.value)) {
                errorMessage.textContent = 'Họ và tên không được chứa số hoặc ký tự đặc biệt.';
            } else {
                errorMessage.textContent = ''; // Xóa cảnh báo khi nhập đúng
            }
        });

        // Kiểm tra tên đăng nhập (thời gian thực)
        usernameInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('username_error');
            if (!/^[a-zA-Z0-9]*$/.test(usernameInput.value)) {
                errorMessage.textContent = 'Tên đăng nhập chỉ được chứa chữ cái và số, không chứa dấu câu.';
            } else {
                errorMessage.textContent = ''; // Xóa cảnh báo khi nhập đúng
            }
        });


        // Kiểm tra số điện thoại (thời gian thực)
        phoneInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('phone_error');
            // Loại bỏ các ký tự không phải là số
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
            // Tự động thêm số 0 vào đầu nếu số nhập không bắt đầu bằng 0
            if (phoneInput.value.length > 0 && !phoneInput.value.startsWith('0')) {
                phoneInput.value = '0' + phoneInput.value;
            }
            // Giới hạn tối đa 10 chữ số
            if (phoneInput.value.length > 11) {
                phoneInput.value = phoneInput.value.slice(0, 11);
                errorMessage.textContent = 'Số điện thoại chỉ được chứa tối đa 10 chữ số.';
            } else {
                errorMessage.textContent = ''; // Xóa cảnh báo khi nhập đúng
            }
        });
        // Kiểm tra ngày sinh đầy đủ và hợp lệ
        dateOfBirthInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('date_of_birth_error');
            const dobValue = dateOfBirthInput.value; // Lấy giá trị ngày sinh dạng chuỗi
            const currentDate = new Date(); // Ngày hiện tại

            // Kiểm tra định dạng
            const [year, month, day] = dobValue.split('-').map(Number);

            if (!year || !month || !day) {
                errorMessage.textContent = 'Vui lòng nhập đầy đủ ngày, tháng, năm.';
                return;
            }

            if (year < 1900 || year === 1) {
                errorMessage.textContent = 'Năm sinh không hợp lệ (phải từ 1900 trở đi).';
                return;
            }

            // Tạo đối tượng ngày để kiểm tra tính hợp lệ
            const dobDate = new Date(dobValue);
            if (isNaN(dobDate.getTime())) {
                errorMessage.textContent = 'Vui lòng nhập ngày sinh hợp lệ.';
                return;
            }

            // Tính tuổi
            const age = currentDate.getFullYear() - dobDate.getFullYear();
            const monthDiff = currentDate.getMonth() - dobDate.getMonth();
            const dayDiff = currentDate.getDate() - dobDate.getDate();
            const isUnder18 = age < 18 || (age === 18 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)));

            if (isUnder18) {
                errorMessage.textContent = 'Bạn phải đủ 18 tuổi để đăng ký.';
            } else {
                errorMessage.textContent = ''; // Xóa thông báo lỗi nếu hợp lệ
            }
        });
        // Kiểm tra địa chỉ (thời gian thực)
        addressInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('address_error');
            if (/^\d+$/.test(addressInput.value.trim())) {
                errorMessage.textContent = 'Địa chỉ không được toàn là số.';
            } else {
                errorMessage.textContent = ''; // Xóa cảnh báo khi nhập đúng
            }
        });

        // Kiểm tra tên công ty (thời gian thực)
        companyInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('company_error');
            if (/^\d+$/.test(companyInput.value.trim())) {
                errorMessage.textContent = 'Tên công ty không được toàn là số.';
            } else {
                errorMessage.textContent = ''; // Xóa cảnh báo khi nhập đúng
            }
        });
        // Hàm kiểm tra định dạng email hợp lệ
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Biểu thức regex kiểm tra email
            return emailRegex.test(email);
        }

        // Sự kiện kiểm tra khi người dùng nhập email
        emailInput.addEventListener('input', function () {
            const errorMessage = document.getElementById('email_error');
            const emailValue = emailInput.value.trim(); // Lấy giá trị email, loại bỏ khoảng trắng

            if (!emailValue) {
                errorMessage.textContent = 'Vui lòng nhập email.';
            } else if (!isValidEmail(emailValue)) {
                errorMessage.textContent = 'Định dạng email không hợp lệ. Vui lòng nhập lại.';
            } else {
                errorMessage.textContent = ''; // Xóa thông báo lỗi khi nhập đúng
            }
        });

        // Xử lý khi nhấn "Đăng ký"
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn gửi form để kiểm tra trước
            let hasError = false;

            // Clear all previous errors
            document.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
            });

            // Full name validation
            const fullName = document.getElementById('full_name');
            if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(fullName.value.trim())) {
                document.getElementById('full_name_error').textContent = 'Họ và tên không được chứa số hoặc ký tự đặc biệt.';
                hasError = true;
            }

            // Phone validation
            const phone = document.getElementById('phone');
            if (!/^\d{10}$/.test(phone.value.trim())) {
                document.getElementById('phone_error').textContent = 'Số điện thoại phải gồm 10 chữ số và không chứa ký tự đặc biệt.';
                hasError = true;
            }

            const dobValue = dateOfBirthInput.value;
            const [year, month, day] = dobValue.split('-').map(Number);

            if (!year || !month || !day) {
                document.getElementById('date_of_birth_error').textContent = 'Vui lòng nhập đầy đủ ngày, tháng, năm.';
                hasError = true;
            } else if (year < 1900 || year === 1) {
                document.getElementById('date_of_birth_error').textContent = 'Năm sinh không hợp lệ (phải từ 1900 trở đi).';
                hasError = true;
            } else {
                const dobDate = new Date(dobValue);
                const currentDate = new Date();
                const age = currentDate.getFullYear() - dobDate.getFullYear();
                const monthDiff = currentDate.getMonth() - dobDate.getMonth();
                const dayDiff = currentDate.getDate() - dobDate.getDate();
                const isUnder18 = age < 18 || (age === 18 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)));

                if (isUnder18) {
                    document.getElementById('date_of_birth_error').textContent = 'Bạn phải đủ 18 tuổi để đăng ký.';
                    hasError = true;
                }
            }

            // Address validation
            const address = document.getElementById('address');
            if (/^\d+$/.test(address.value.trim())) {
                document.getElementById('address_error').textContent = 'Địa chỉ không được chứa toàn là số.';
                hasError = true;
            }

            // Company validation
            const company = document.getElementById('company');
            if (/^\d+$/.test(company.value.trim())) {
                document.getElementById('company_error').textContent = 'Tên công ty không được chứa toàn là số.';
                hasError = true;
            }

            // Email validation
            const email = document.getElementById('email');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                document.getElementById('email_error').textContent = 'Email không hợp lệ.';
                hasError = true;
            }

            // Username validation
            const username = usernameInput; // Sử dụng biến đã định nghĩa
            if (!/^[a-zA-Z0-9]+$/.test(username.value.trim())) {
                document.getElementById('username_error').textContent = 'Tên đăng nhập chỉ được chứa chữ cái và số, không chứa dấu câu.';
                hasError = true;
            }

            // Password validation
            const password = document.getElementById('password');
            if (password.value.length < 6 || !/[A-Z]/.test(password.value) || !/[0-9]/.test(password.value) || !/[!@#$%^&*]/.test(password.value)) {
                document.getElementById('password_error').textContent = 'Mật khẩu yếu: nên chứa ít nhất 6 ký tự bao gồm chữ in hoa, số và ký tự đặc biệt.';
            }



            // Password confirmation validation
            const passwordConfirm = document.getElementById('password_confirm');
            if (password.value !== passwordConfirm.value) {
                document.getElementById('password_confirm_error').textContent = 'Mật khẩu xác nhận không khớp.';
                hasError = true;
            }
            // Kiểm tra email
            const emailValue = emailInput.value.trim();
            if (!emailValue) {
                document.getElementById('email_error').textContent = 'Vui lòng nhập email.';
                hasError = true;
            } else if (!isValidEmail(emailValue)) {
                document.getElementById('email_error').textContent = 'Định dạng email không hợp lệ. Vui lòng nhập lại.';
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
