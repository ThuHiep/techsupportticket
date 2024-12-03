<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
</head>
<body>
<div class="container mt-4">
    <h1>Danh sách khách hàng</h1>
    <a href="{{ route('backend.customers.create') }}" class="btn btn-primary mb-3">Thêm khách hàng</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->customer_id }}</td>
                <td>{{ $customer->full_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->created_at }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
