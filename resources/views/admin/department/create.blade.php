<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới phòng ban</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/department/create.css') }}">
    <style>
        /* Khi sidebar ở trạng thái bình thường */
        body .container {
            width: calc(98%); /* Độ rộng sau khi trừ sidebar */
            transition: all 0.3s ease-in-out;
        }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%); /* Mở rộng nội dung khi sidebar thu nhỏ */
            transition: all 0.3s ease-in-out;
        }
        .required {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Thêm mới phòng ban</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('department.store') }}" method="POST">
        @csrf

        <!-- Row chứa các trường trên cùng 1 dòng -->
        <div class="form-group-row">
            <div class="form-group">
                <label for="department_id">Mã phòng ban:<span class="required">*</span></label>
                <input type="text" name="department_id" id="department_id" value="{{ $nextId ?? '' }}" readonly>
                @error('department_id')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        

            <div class="form-group">
                <label for="department_name">Tên phòng ban:<span class="required">*</span></label>
                <input type="text" name="department_name" id="department_name" value="{{ old('department_name') }}" required>
                @error('department_name')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Trạng thái:<span class="required">*</span></label>
                <select name="status" id="status">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @error('status')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="add-department-btn">Thêm mới</button>
            <a href="{{ route('department.index') }}" class="cancel-btn">Quay lại</a>
        </div>
    </form>
</div>
</body>
</html>
