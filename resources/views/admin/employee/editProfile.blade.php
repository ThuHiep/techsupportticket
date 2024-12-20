<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin/css/employee/style_edit.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin/css/employee/style_edit.css') }}">
<style>
    /* Khi sidebar ở trạng thái bình thường */
    body .container {
        width: calc(98%);
        /* Độ rộng sau khi trừ sidebar */
        transition: all 0.3s ease-in-out;
    }

    /* Khi sidebar thu nhỏ */
    body.mini-navbar .container {
        width: calc(98%);
        /* Mở rộng nội dung khi sidebar thu nhỏ */
        transition: all 0.3s ease-in-out;
    }

    .required {
        color: red;
        font-size: 14px;
    }
</style>

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
                            <label for="user_id" class="form-label">Mã nhân viên<span class="required">*</span></label>
                            <input type="text" id="user_id" name="user_id" class="form-control"
                                value="{{ $logged_user->user_id }}" readonly required>
                        </div>


                        <div class="form-group col-md-4">
                            <label for="full_name" class="form-label">Tên nhân viên<span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control"
                                value="{{ $logged_user->full_name }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="username" class="form-label">Tên tài khoản<span class="required">*</span></label>
                            <input type="text" id="username" name="username" class="form-control"
                                value="{{ $logged_user->user->username }}" required>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="text" id="email" name="email" class="form-control"
                                value="{{ $logged_user->email }}" required>
                        </div>
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

                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control"
                                value="{{ $logged_user->phone }}" required>
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
                <button type="submit" class="btn btn-success me-3">Cập nhật</button>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Hủy</a>
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
</script>