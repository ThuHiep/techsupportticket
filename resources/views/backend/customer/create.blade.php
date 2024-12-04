<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng mới</title>
</head>
<body>
<div class="container mt-4">
    <h1>Thêm khách hàng mới</h1>
    <form action="{{ route('backend.customer.store') }}" method="POST">
        @csrf

        <!-- Hiển thị customer_id (không chỉnh sửa được) -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Mã khách hàng</label>
            <input type="text" id="customer_id" name="customer_id" class="form-control"
                   value="{{ $randomId}}" required>
        </div>

        <!-- Hiển thị tax_id (không chỉnh sửa được) -->
        <div class="mb-3">
            <label for="tax_id" class="form-label">Mã số thuế</label>
            <input type="text" id="tax_id" name="tax_id" class="form-control"
                   value="{{ $taxId }}" readonly>
        </div>

        <!-- Tên khách hàng (cho phép nhập) -->
        <div class="mb-3">
            <label for="full_name" class="form-label">Tên khách hàng</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>

        <!-- Email (cho phép nhập) -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <!-- Nút Lưu -->
        <button type="submit" class="btn btn-success">Lưu khách hàng</button>
        <!-- Nút Quay lại -->
        <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
