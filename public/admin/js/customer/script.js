function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('preview-img');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('tax_id').addEventListener('input', function () {
    // Chỉ cho phép nhập số
    this.value = this.value.replace(/[^0-9]/g, '');

    // Chặn nhập nếu có hơn 9 chữ số
    if (this.value.length > 9) {
        this.value = this.value.slice(0, 9); // Giới hạn chỉ còn 9 chữ số
    }
});
document.getElementById('full_name').addEventListener('input', function () {
    // Thay thế tất cả các ký tự số
    this.value = this.value.replace(/[0-9]/g, '');
});
document.getElementById('company').addEventListener('input', function () {
    this.value = this.value.replace(/^[0-9]/g, ''); // Không cho phép bắt đầu bằng số
    this.value = this.value.replace(/[^a-zA-Z0-9.\-]/g, ''); // Cho phép chữ cái, số, dấu '.' và '-'
});
document.getElementById('software').addEventListener('input', function () {
    this.value = this.value.replace(/^[0-9]/g, ''); // Không cho phép bắt đầu bằng số
    this.value = this.value.replace(/[^a-zA-Z0-9.\-]/g, ''); // Cho phép chữ cái, số, dấu '.' và '-'
});
document.getElementById('phone').addEventListener('input', function () {
    // Chỉ cho phép nhập số
    this.value = this.value.replace(/[^0-9]/g, '');

    // Nếu nhập số, kiểm tra xem có bắt đầu bằng 0 không
    if (this.value.length > 0 && this.value[0] !== '0') {
        this.value = '0' + this.value; // Thêm 0 vào đầu nếu chưa có
    }

    // Chặn nhập nếu có hơn 10 chữ số
    if (this.value.length > 10) {
        this.value = this.value.slice(0, 10); // Giới hạn chỉ còn 10 chữ số
    }
});

// Ràng buộc cho trường website
document.getElementById('website').addEventListener('input', function () {
    // Chỉ cho phép chữ cái, số, dấu '.' và không bắt đầu bằng số
    this.value = this.value.replace(/^[0-9]/g, ''); // Không cho phép bắt đầu bằng số
    this.value = this.value.replace(/[^a-zA-Z0-9.\-]/g, ''); // Cho phép chữ cái, số, dấu '.' và '-'
});

// Ràng buộc cho trường email
document.getElementById('email').addEventListener('input', function () {
    // Chỉ cho phép ký tự hợp lệ trong email
    this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, ''); // Chỉ cho phép chữ cái, số, '@', '.'
});

// Reset mật khẩu
document.getElementById('reset-password').addEventListener('click', function () {
    const newPassword = Math.random().toString(36).slice(-8);
    document.getElementById('password').value = newPassword;
});

// Kiểm tra định dạng email
document.getElementById('email').addEventListener('blur', function () {
    const emailInput = this;
    const emailError = document.getElementById('email-error');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(emailInput.value)) {
        emailInput.classList.add('is-invalid');
        emailError.style.display = 'block';
    } else {
        emailInput.classList.remove('is-invalid');
        emailError.style.display = 'none';
    }
});

// Hàm kiểm tra các trường khác
function validateField(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);

    input.addEventListener('blur', function () {
        if (input.value.trim() === '') {
            input.classList.add('is-invalid');
            error.style.display = 'block';
        } else {
            input.classList.remove('is-invalid');
            error.style.display = 'none';
        }
    });
}

// Gọi hàm kiểm tra cho từng trường
validateField('tax_id', 'tax-error');
validateField('full_name', 'name-error');
validateField('company', 'company-error');
validateField('website', 'website-error');
validateField('software', 'software-error');
validateField('address', 'address-error');

// Kiểm tra độ dài và hiển thị cảnh báo khi mất tiêu điểm (blur)
document.getElementById('phone').addEventListener('blur', function () {
    const phoneInput = this;
    const phoneError = document.getElementById('phone-error');

    // Cần có đúng 10 chữ số và bắt đầu bằng 0
    if (phoneInput.value.length !== 10 || phoneInput.value[0] !== '0') {
        phoneInput.classList.add('is-invalid');
        phoneError.style.display = 'block';
    } else {
        phoneInput.classList.remove('is-invalid');
        phoneError.style.display = 'none';
    }
});
