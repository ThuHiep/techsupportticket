<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_edit.css') }}">
    <title>Chỉnh sửa khách hàng</title>
</head>
<style>
    /* Container của loading spinner */
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
        display: none; /* Mặc định ẩn */
    }

    /* Spinner */
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
    <h1 style="text-align: left">Chỉnh sửa thông tin khách hàng</h1>

    <form action="{{ route('customer.update', $customers->customer_id) }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" id="username" name="username" class="form-control" value="{{ $customers->user->username }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="full_name" class="form-label">Tên khách hàng<span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ $customers->full_name }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status" class="form-label">Trạng thái<span class="required">*</span></label>
                        <select id="status" name="status" class="form-control" onchange="updateStatusStyle(this)">
                            <option value="active" data-color="green" {{ $customers->status === 'active' ? 'selected' : '' }}>
                                Hoạt động
                            </option>
                            <option value="inactive" data-color="red" {{ $customers->status === 'inactive' ? 'selected' : '' }}>
                                Ngừng hoạt động
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Ngày sinh + giới tính + số điện thoại -->
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ $customers->date_of_birth->toDateString() }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Nam" {{ $customers->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ $customers->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $customers->phone }}" required>
                    </div>
                </div>

                <!-- Email + Software + Công ty -->
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control" value="{{ $customers->company }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" value="{{ $customers->tax_id }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="software" class="form-label">Phần mềm<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" value="{{ $customers->software }}">
                    </div>

                </div>

                <!-- Địa chỉ + Website -->
                <div class="row mb-3 address-website-container">
                    <div class="form-group col-4">
                        <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ $customers->address }}" required>
                    </div>
                    <div class="form-group col-4">
                        <label for="email" class="form-label">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $customers->email }}">
                    </div>
                    <div class="form-group col-4">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" value="{{ $customers->website }}">
                    </div>
                </div>


            </div>

            <!-- Cột bên phải cho hình ảnh đại diện -->
            <div class="col-md-3">
                <div class="container-img">
                    <div class="form-group">
                        <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                        <div class="custom-file-upload">
                            <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <label for="profile_image" class="custom-file-label">Chọn khác</label>
                            <div class="image-preview">
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img"
                                         src="{{ $customers->profile_image ? asset('admin/img/customer/' . $customers->profile_image) : asset('admin/img/customer/default.png') }}"
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
            <a href="{{ route('customer.index') }}" class="btn btn-cancel">Hủy</a>
        </div>

    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('admin/js/customer/script.js')}}"></script>
    <script>
        document.getElementById('selectedStatus').onclick = function() {
            const options = document.querySelector('.options');
            options.style.display = options.style.display === 'block' ? 'none' : 'block';
        };

        function selectStatus(value) {
            document.getElementById('status').value = value;
            const selectedText = value === 'active' ? 'Hoạt động' : 'Ngừng hoạt động';
            const selectedColor = value === 'active' ? 'green' : 'red';
            document.getElementById('selectedStatus').innerHTML = `
            <span style="color:${selectedColor}; font-size: 24px; margin-right: 5px;">&#8226;</span>
            ${selectedText}
        `;
            document.querySelector('.options').style.display = 'none';
        }

        window.onclick = function(event) {
            if (!event.target.matches('.selected')) {
                const options = document.querySelector('.options');
                if (options.style.display === 'block') {
                    options.style.display = 'none';
                }
            }
        };
    </script>
    <script>
        // Bắt sự kiện submit form
        const form = document.querySelector('form');
        const loadingOverlay = document.querySelector('.loading-overlay');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Chặn submit để test
            loadingOverlay.style.display = 'flex';
            setTimeout(() => {
                form.submit(); // Submit form sau 3 giây
            }, 3000);
        });

    </script>

</div>
</body>

</html>
