<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khách hàng</title>
</head>
<body>
<div class="container mt-4">
    <h1>Thêm khách hàng mới</h1>
    <form action="{{ route('backend.customer.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên khách hàng</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
</body>
</html>
