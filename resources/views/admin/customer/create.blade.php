<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style_create.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
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
                    <div class="form-group col-md-6">
                        <label for="website" class="form-label">Website<span class="required">*</span></label>
                        <input type="text" id="website" name="website" class="form-control" required value="{{ old('website') }}">
                        <small id="website-error" class="text-danger" style="display: none;">Vui lòng nhập website!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="software" class="form-label">Phần mềm<span class="required">*</span></label>
                        <input type="text" id="software" name="software" class="form-control" required value="{{ old('software') }}">
                        <small id="software-error" class="text-danger" style="display: none;">Vui lòng nhập phần mềm!</small>
                    </div>

                    
                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label for="company" class="form-label">Công ty<span class="required">*</span></label>
                        <input type="text" id="company" name="company" class="form-control" required value="{{ old('company') }}">
                        <small id="company-error" class="text-danger" style="display: none;">Vui lòng nhập công ty!</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tax_id" class="form-label">Mã số thuế<span class="required">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-control" required pattern="\d{1,9}" title="Mã số thuế chỉ được phép tối đa 9 chữ số" value="{{ old('tax_id') }}">
                        <small id="tax-error" class="text-danger" style="display: none;">Vui lòng nhập mã số thuế!</small>
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
</body>
</html>
