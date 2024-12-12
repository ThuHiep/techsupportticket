<link rel="stylesheet" href="{{ asset('admin/css/customer/style_create.css') }}">
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
        <h1 style="text-align: left">Thêm nhân viên mới</h1>
        <form action="{{ route('employee.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="employee_id" class="form-label">Mã nhân viên<span class="required">*</span></label>
                            <input type="text" id="employee_id" name="employee_id" class="form-control" value="{{ $randomId }}" readonly required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="full_name" class="form-label">Tên nhân viên<span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-group col-md-6">
                            <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-6">
                            <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                            <input type="text" id="address" name="address" class="form-control" required>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút hành động -->
            <div class="button-container">
                <button type="submit" class="btn btn-success me-3">Thêm mới</button>
                <a href="{{ route('employee.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</body>


<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-img');
            output.src = reader.result;
            output.style.display = 'block';
            var label = document.getElementById('file-label');
            label.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>