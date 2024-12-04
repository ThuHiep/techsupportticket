<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa khách hàng</title>
</head>
<body>
<div class="container mt-4">
    <h1>Chỉnh sửa thông tin khách hàng</h1>
    <form action="{{ route('backend.customer.update', $customers->customer_id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Trường customer_id chỉ hiển thị -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Mã khách hàng</label>
            <input type="text" id="customer_id" name="customer_id" class="form-control"
                   value="{{ $customers->customer_id }}" readonly>
        </div>

        <!-- Tên khách hàng -->
        <div class="mb-3">
            <label for="name" class="form-label">Tên khách hàng</label>
            <input type="text" id="name" name="name" class="form-control"
                   value="{{ $customers->full_name }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="{{ $customers->user->email }}" required>
        </div>

        <!-- Nút Lưu -->
        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
