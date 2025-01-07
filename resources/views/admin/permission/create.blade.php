<link rel="stylesheet" href="{{ asset('admin/css/permission/style_create.css') }}">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* Overlay che toàn màn hình */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        /* Mặc định ẩn */
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Vòng tròn xoay */
    .loading-spinner {
        border: 8px solid #f3f3f3;
        /* Màu nền */
        border-top: 8px solid #3498db;
        /* Màu xoay */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container">
        <h1 style="text-align: left">Thêm tài khoản mới</h1>
        <form action="{{ route('permission.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="full_name" class="form-label">Tên người dùng<span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}" value="{{ old('full_name') }}" required>
                            @error('full_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="role_id" class="form-label">Vai trò<span class="required">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="1">Quản trị viên hệ thống</option>
                                <option value="2">Nhân viên hỗ trợ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-6 form-group-2">
                            <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 form-group-2">
                            <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" required>
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-6 form-group-3">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 form-group-3">
                            <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                            <input type="text" id="address" name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" required>
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Tùy chọn mật khẩu -->
                        <div class="form-group col-md-4">
                            <label for="password_option" class="form-label">Tùy chọn mật khẩu<span class="required">*</span></label>
                            <select class="form-select" id="password_option" name="password_option" required>
                                <option value="random" selected>Tạo mật khẩu ngẫu nhiên</option>
                                <option value="manual">Nhập mật khẩu</option>
                            </select>
                        </div>

                        <!-- Trường nhập mật khẩu -->
                        <div class="form-group col-md-4 password-input-fields" style="display: none;">
                            <label for="password" class="form-label">Mật khẩu<span class="required">*</span></label>
                            <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <i class="bx bx-show toggle-password icon" data-target="password"></i>
                            <span class="error-message" id="password_error"></span>
                        </div>

                        <!-- Trường xác nhận mật khẩu -->
                        <div class="form-group col-md-4 password-input-fields" style="display: none;">
                            <label for="confirm-password" class="form-label">Xác nhận mật khẩu<span class="required">*</span></label>
                            <input type="password" id="confirm-password" name="confirm-password" class="form-control">
                            <i class="bx bx-show toggle-password icon" data-target="confirm-password"></i>
                            <span class="error-message" id="password_confirm_error"></span>
                        </div>
                    </div>
                </div>

                <!-- Cột bên phải cho hình ảnh đại diện -->
                <div class="col-md-3 grouped-fields">

                    <div class="container-img">
                        <div class="form-group">
                            <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                            <div class="custom-file-upload">
                                <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <label for="profile_image" class="custom-file-label">Chọn mới</label>
                                <div class="image-preview">
                                    <div id="image-preview" class="image-preview">
                                        <img id="preview-img" src="" alt="Image Preview" style="display:none;">
                                    </div>
                                </div>
                                @error('profile_image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút hành động -->
            <div class="button-container">
                <button type="submit" class="btn btn-add-cus me-3">Thêm mới</button>
                <a href="{{ route('permission.index') }}" class="btn btn-cancel">Quay lại</a>
            </div>
        </form>
    </div>
</body>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');
        const loadingOverlay = document.querySelector('.loading-overlay');
        const passwordOptionSelect = document.getElementById('password_option');
        const passwordFields = document.querySelectorAll('.password-input-fields');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('confirm-password');
        const errorMessages = document.querySelectorAll('.error-message');

        function clearErrors() {
            errorMessages.forEach(errorElement => (errorElement.textContent = ''));
        }

        function updateHints(passwordValue) {
            const errorMessage = document.getElementById('password_error');
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

        if (passwordOptionSelect) {
            passwordOptionSelect.addEventListener('change', function() {
                if (this.value === 'manual') {
                    passwordFields.forEach(field => (field.style.display = 'block'));
                } else {
                    passwordFields.forEach(field => {
                        field.style.display = 'none';
                        const input = field.querySelector('input');
                        if (input) input.value = ''; // Reset giá trị input
                    });
                }
                clearErrors();
            });
        }

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

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                updateHints(passwordInput.value);
            });
        }

        // Xử lý submit form
        form.addEventListener('submit', function(e) {
            let hasError = false;

            loadingOverlay.style.display = 'flex';

            if (passwordOptionSelect && passwordOptionSelect.value === 'manual') {
                if (
                    passwordInput.value.length < 8 ||
                    !/[A-Z]/.test(passwordInput.value) ||
                    !/[0-9]/.test(passwordInput.value) ||
                    !/[!@#$%^&*]/.test(passwordInput.value)
                ) {
                    document.getElementById('password_error').textContent =
                        'Mật khẩu yếu: phải chứa ít nhất 8 ký tự bao gồm chữ in hoa, số và ký tự đặc biệt.';
                    hasError = true;
                }

                if (passwordInput.value !== passwordConfirmInput.value) {
                    document.getElementById('password_confirm_error').textContent =
                        'Mật khẩu xác nhận không khớp.';
                    hasError = true;
                }
            }

            if (hasError) {
                e.preventDefault();
                loadingOverlay.style.display = 'none'; // Ẩn spinner nếu có lỗi
            }
        });
    });
</script>