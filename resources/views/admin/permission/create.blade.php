<link rel="stylesheet" href="{{ asset('admin/css/permission/style_create.css') }}">

<body>
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

                        <div class="form-group col-md-4">
                            <label for="date_of_birth" class="form-label">Ngày sinh<span class="required">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="phone" class="form-label">Số điện thoại<span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" required>
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-6">
                            <label for="address" class="form-label">Địa chỉ<span class="required">*</span></label>
                            <input type="text" id="address" name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" required>
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
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
    document.addEventListener("DOMContentLoaded", function() {
        var errorFields = document.querySelector('form').querySelectorAll('.is-invalid');
        console.log('Các trường có lỗi:', errorFields);

        errorFields.forEach(function(field) {
            field.value = '';
        });
    });
</script>
