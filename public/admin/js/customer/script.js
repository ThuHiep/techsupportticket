// Hàm kiểm tra lỗi
function hasErrors() {
    const invalidFields = document.querySelectorAll('.is-invalid');
    return invalidFields.length > 0;
}

// Sự kiện khi gửi form
document.querySelector('form').addEventListener('submit', function (event) {
    if (hasErrors()) {
        event.preventDefault(); // Ngăn không cho gửi form
        alert('Vui lòng kiểm tra các trường nhập liệu và sửa lỗi trước khi gửi.');
    }
});

function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('preview-img');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Hàm kiểm tra trường nhập cho full_name
document.getElementById('full_name').addEventListener('input', function () {
    // Thay thế tất cả các ký tự số
    this.value = this.value.replace(/[0-9]/g, '');
});

// Kiểm tra rỗng khi mất tiêu điểm (blur)
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

    input.addEventListener('input', function () {
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
validateField('full_name', 'name-error');
validateField('date_of_birth', 'date-error');
validateField('phone', 'phone-error');
validateField('company', 'company-error');
validateField('tax_id', 'tax-error');
validateField('software', 'software-error');
validateField('address', 'address-error');
validateField('email', 'email-error');
validateField('website', 'website-error');

// Kiểm tra ngày sinh đủ 18 tuổi và nhập đầy đủ
document.getElementById('date_of_birth').addEventListener('blur', function () {
    const dateInput = this;
    const dateError = document.getElementById('date-error');
    const incompleteError = document.getElementById('date-incomplete-error');
    const birthDate = new Date(dateInput.value);

    // Kiểm tra xem ngày nhập có hợp lệ không
    if (!dateInput.value) {
        dateInput.classList.add('is-invalid');
        incompleteError.style.display = 'block';
        dateError.style.display = 'none';
        return;
    } else {
        incompleteError.style.display = 'none';
    }

    const year = birthDate.getFullYear();
    if (year < 1500) {
        dateInput.classList.add('is-invalid');
        dateError.textContent = "Năm phải lớn hơn hoặc bằng 1500!";
        dateError.style.display = 'block';
        return;
    }

    const today = new Date();
    let age = today.getFullYear() - year;
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    if (age < 18) {
        dateInput.classList.add('is-invalid');
        dateError.textContent = "Bạn phải đủ 18 tuổi!";
        dateError.style.display = 'block';
    } else {
        dateInput.classList.remove('is-invalid');
        dateError.style.display = 'none';
    }
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

// Kiểm tra số điện thoại
// Chỉ cho phép nhập số trong trường số điện thoại
document.getElementById('phone').addEventListener('input', function () {
    const phoneInput = this;
    // Chỉ giữ lại các ký tự số
    phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
    if(phoneInput.value.length ===1 && phoneInput.value !== '0'){
        phoneInput.value = '0' + phoneInput.value;
    }
    if(phoneInput.value.length > 10){
        phoneInput.value = phoneInput.value.slice(0,10);
    }
});

// Kiểm tra số điện thoại khi mất tiêu điểm (blur)
document.getElementById('phone').addEventListener('blur', function () {
    const phoneInput = this;
    const phoneError = document.getElementById('phone-error');

    // Lấy giá trị số điện thoại
    const phoneValue = phoneInput.value;

    // Kiểm tra xem có nhập vào không
    if (!phoneValue) {
        phoneInput.classList.add('is-invalid');
        phoneError.textContent = "Số điện thoại không được để trống!";
        phoneError.style.display = 'block';
        return;
    }

    // Kiểm tra có phải là 10 chữ số
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phoneValue)) {
        phoneInput.classList.add('is-invalid');
        phoneError.textContent = "Số điện thoại phải là 10 chữ số!";
        phoneError.style.display = 'block';
    } else {
        phoneInput.classList.remove('is-invalid');
        phoneError.style.display = 'none';
    }
});

// Hàm kiểm tra trường nhập cho address, software, company, website
function validateAlphanumeric(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);

    input.addEventListener('input', function () {
        // Kiểm tra xem có phải là toàn bộ số không
        if (/^\d+$/.test(input.value.trim())) {
            input.classList.add('is-invalid');
            error.textContent = "Trường này không được nhập toàn bộ số!";
            error.style.display = 'block';
        } else {
            input.classList.remove('is-invalid');
            error.style.display = 'none';
        }
    });

    input.addEventListener('blur', function () {
        if (input.value.trim() === '') {
            input.classList.add('is-invalid');
            error.textContent = "Trường này không được để trống!";
            error.style.display = 'block';
        } else {
            input.classList.remove('is-invalid');
            error.style.display = 'none';
        }
    });
}

// Gọi hàm kiểm tra cho từng trường
validateAlphanumeric('address', 'address-error');
validateAlphanumeric('software', 'software-error');
validateAlphanumeric('company', 'company-error');
validateAlphanumeric('website', 'website-error');


document.getElementById('tax_id').addEventListener('input', function () {
    const phoneInput = this;
    // Chỉ giữ lại các ký tự số
    phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
    if(phoneInput.value.length > 9){
        phoneInput.value = phoneInput.value.slice(0,9);
    }
});

// Kiểm tra số điện thoại khi mất tiêu điểm (blur)
document.getElementById('tax_id').addEventListener('blur', function () {
    const taxInput = this; // Đổi tên biến thành taxInput
    const taxError = document.getElementById('tax-error'); // Đổi tên biến thành taxError

    // Lấy giá trị mã số thuế
    const taxValue = taxInput.value;

    // Kiểm tra xem có nhập vào không
    if (!taxValue) {
        taxInput.classList.add('is-invalid');
        taxError.textContent = "Mã số thuế không được để trống!";
        taxError.style.display = 'block';
        return;
    }

    // Kiểm tra có phải là 9 chữ số
    const taxRegex = /^[0-9]{9}$/; // Thay đổi regex để kiểm tra 9 chữ số
    if (!taxRegex.test(taxValue)) {
        taxInput.classList.add('is-invalid');
        taxError.textContent = "Mã số thuế phải là 9 chữ số!";
        taxError.style.display = 'block';
    } else {
        taxInput.classList.remove('is-invalid');
        taxError.style.display = 'none';
    }
});
