<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin/css/employee/style_edit.css') }}">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<body>
    <div class="container">
        <h1 style="text-align: left">Chỉnh sửa thông tin hồ sơ</h1>
        <form action="{{ route('employee.updateProfile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Cột bên trái (3/4) và cột bên phải (1/4) -->
            <div class="row mb-3">
                <!-- Cột bên trái -->
                <div class="col-md-9">
                    <!-- Mã KH + Mã số thuế + Tên KH -->
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="username" class="form-label">Tên tài khoản<span class="required">*</span></label>
                            <input type="text" id="username" name="username" class="form-control"
                                value="{{ $logged_user->user->username }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="full_name" class="form-label">Tên nhân viên<span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control"
                                value="{{ $logged_user->full_name }}" required>
                        </div>
                        

                    </div>

                    <div class="row mb-3">
                       
                        <div class="form-group col-md-4">
                            <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                value="{{ $logged_user->date_of_birth->toDateString() }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="Nam" {{ $logged_user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $logged_user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control"
                                value="{{ $logged_user->phone }}" required>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="text" id="email" name="email" class="form-control"
                                value="{{ $logged_user->email }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="{{ $logged_user->address }}" required>
                        </div>
                    </div>
                </div>

                <!-- Cột bên phải cho hình ảnh đại diện -->
                <div class="col-md-3">
                    <div class="container-img">
                        <div class="form-group">
                            <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                            <div class="custom-file-upload">
                                <input type="file" id="profile_image" name="profile_image" class="form-control"
                                    accept="image/*" onchange="previewImage(event)">
                                <label for="profile_image" class="custom-file-label">Chọn khác</label>

                                <div class="image-preview">


                                    <div id="image-preview" class="image-preview">
                                        <img id="preview-img"
                                            src="{{ $logged_user->profile_image ? asset('admin/img/employee/' . $logged_user->profile_image) : asset('admin/img/customer/default.png') }}"
                                            alt="Hình đại diện">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút hành động -->
            <div class="button-container">
                <button type="submit" class="btn btn-edit me-3">Cập nhật</button>
                <div class="btn btn-edit me-3" id="openForm">Thay đổi mật khẩu</div>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal reset-password-box" id="registrationForm">
        <div class="reset-password-header">
            <span>Đổi mật khẩu</span>
        </div>
        <form action="{{ route('employee.changePass') }}" method="POST" enctype="multipart/form-data" class="reset-password-form">
            @csrf
            @method('PUT')

            <div class="input_box">
                <input type="password" name="old-password" id="old-password" class="input-field" required>
                <label for="old-password" class="label">Mật khẩu cũ</label>
                <i class="bx bx-show toggle-password icon" data-target="old-password"></i>
                @if ($errors->has('old-password'))
                <div class="error-message">{{ $errors->first('old-password') }}</div>
                @endif
            </div>

            <div class="input_box">
                <input type="password" name="new-password" id="new-password" class="input-field" value="{{ old('new-password') }}" required>
                <label for="new-password" class="label">Mật khẩu mới</label>
                <i class="bx bx-show toggle-password icon" data-target="new-password"></i>
                <span class="error-message" id="password_error"></span>
                <div class="password-hint">
                    <strong class="strong1">Gợi ý để tạo mật khẩu an toàn:</strong>
                    <div class="hint-list">
                        <ul>
                            <li class="hint" id="hint_length">Tối thiểu 8 ký tự</li>
                            <li class="hint" id="hint_uppercase">1 chữ cái in hoa</li>
                            <li class="hint" id="hint_number">1 số</li>
                            <li class="hint" id="hint_special">1 ký tự đặc biệt</li>
                            <li class="hint" id="hint_lowercase">1 chữ thường</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="input_box">
                <input type="password" name="confirm-password" id="confirm-password" class="input-field" value="{{ old('confirm-password') }}" required>
                <label for="confirm-password" class="label">Xác nhận mật khẩu</label>
                <i class="bx bx-show toggle-password icon" data-target="confirm-password"></i>
                <span class="error-message" id="password_confirm_error"></span>
            </div>

            <!-- Submit Button -->
            <div class="input_box">
                <input type="submit" class="input-submit" value="Cập nhật mật khẩu">
            </div>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-img');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Thành công!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
    @endif
    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị modal nếu có lỗi từ server
        if ("{{ $errors->any() }}") {
            document.getElementById('registrationForm').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        const passwordInput = document.getElementById('new-password');
        const savedPassword = "{{ old('new-password') }}";
        const passwordConfirmInput = document.getElementById('confirm-password');
        const form = document.querySelector('.reset-password-form');

        if (savedPassword) {
            updateHints(savedPassword);
        }

        // Cập nhật gợi ý khi người dùng nhập mật khẩu mới
        passwordInput.addEventListener('input', function() {
            const passwordValue = passwordInput.value;
            updateHints(passwordValue);
        });

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
    const openFormButton = document.getElementById('openForm');
    const modalPass = document.getElementById('registrationForm');
    const overlay = document.getElementById('modalOverlay');
    openFormButton.addEventListener('click', () => {
        modalPass.style.display = 'block';
        overlay.style.display = 'block';

    });

    overlay.addEventListener('click', () => {
        modalPass.style.display = 'none';
        overlay.style.display = 'none';
        const inputs = modalPass.querySelectorAll("input");
        inputs.forEach(input => {
            if (input.type === "submit") {
                return;
            } else {
                input.value = "";
            }
        });
    });

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

    function updateHints(passwordValue) {
        const errorMessage = document.getElementById('password_error');

        // Reset màu sắc gợi ý
        document.querySelectorAll('.hint').forEach(hint => {
            hint.style.color = 'black';
        });

        // Thay đổi màu sắc dựa trên điều kiện
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

        // Hiển thị thông báo lỗi nếu không đạt
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
    }
</script>