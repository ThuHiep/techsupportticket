<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_create.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;
        /* Mặc định ẩn */
    }

    .spinner {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    /* Animation quay */
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
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="container">
        <h1 style="text-align: left">Thêm khách hàng mới</h1>
        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-9">
                    <div class="row mb-3">

                        {{-- <div class="form-group col-md-4">
                        <label for="username" class="form-label">Tên tài khoản<span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $username) }}" readonly required>
                    </div> --}}

                    <div class="form-group col-md-4">
                        <label for="full_name" class="form-label">Tên khách hàng<span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        <small id="name-error" class="text-danger" style="display: none;">Vui lòng nhập tên khách hàng!</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                        <small id="date-error" class="text-danger" style="display: none;">Bạn phải đủ 18 tuổi!</small>
                        <small id="date-incomplete-error" class="text-danger" style="display: none;">Vui lòng nhập đầy đủ ngày, tháng và năm!</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                </div>
                <div class="row mb-3">

                    <div class="form-group col-md-6">
                        <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" required pattern="\d{10}" title="Số điện thoại phải gồm 10 chữ số" value="{{ old('phone') }}">
                        <small id="phone-error" class="text-danger" style="display: none;">Vui lòng nhập đúng số điện thoại!</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email" class="form-label">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                        @error('email')
                        <small id="email-error" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" required value="{{ old('address') }}">
                        <small id="address-error" class="text-danger" style="display: none;">Vui lòng nhập địa chỉ!</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-6 form-group-2">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" required value="{{ old('website') }}">
                        <small id="website-error" class="text-danger" style="display: none;">Vui lòng nhập website!</small>
                    </div>
                    <div class="form-group col-md-6 form-group-2">
                        <label for="software" class="form-label">Phần mềm<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" required value="{{ old('software') }}">
                        <small id="software-error" class="text-danger" style="display: none;">Vui lòng nhập phần mềm!</small>
                    </div>


                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-6 form-group-3">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control" required value="{{ old('company') }}">
                        <small id="company-error" class="text-danger" style="display: none;">Vui lòng nhập công ty!</small>
                    </div>
                    <div class="form-group col-md-6 form-group-3">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" required pattern="\d{1,9}" title="Mã số thuế chỉ được phép tối đa 9 chữ số" value="{{ old('tax_id') }}">
                        <small id="tax-error" class="text-danger" style="display: none;">Vui lòng nhập mã số thuế!</small>
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
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="button-container">
        <button type="submit" class="btn btn-add-cus me-3">Thêm mới</button>
        <a href="{{ route('customer.index') }}" class="btn btn-cancel">Quay lại</a>
    </div>
    </form>
    </div>
    <script src="{{asset('admin/js/customer/script.js')}}"></script>

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

</body>

</html>