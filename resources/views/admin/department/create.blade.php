<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới Phòng ban</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/department/create.css') }}">
</head>
<body>
<div class="container">
    <h1>Thêm mới Phòng ban</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('department.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="department_id">Mã phòng ban:</label>
            <input type="text" name="department_id" id="department_id" value="{{ $nextId ?? '' }}" readonly>
            @error('department_id')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="department_name">Tên phòng ban:</label>
            <input type="text" name="department_name" id="department_name" value="{{ old('department_name') }}" required>
            @error('department_name')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">Trạng thái:</label>
            <select name="status" id="status">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="add-department-btn">Lưu</button>
            <a href="{{ route('department.index') }}" class="cancel-btn">Hủy</a>
        </div>
    </form>
</div>
</body>
</html>