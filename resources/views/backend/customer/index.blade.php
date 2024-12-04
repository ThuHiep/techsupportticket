<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
</head>
<body>
<div class="container mt-4">
    <h1>Danh sách khách hàng</h1>

    <!-- Thanh tìm kiếm và nút Thêm khách hàng -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Tìm kiếm -->
        <form action="{{ route('backend.customer.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm khách hàng" value="{{ request()->query('search') }}">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>

        <!-- Nút Thêm khách hàng -->
        <a href="{{ route('backend.customer.create') }}" class="btn btn-primary">Thêm khách hàng</a>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Mã khách hàng</th>
            <th>Tên</th>
            <th>Ngày sinh</th>
            <th>Email</th>
            <th>Giới tính</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->customer_id }}</td>
                <td>{{ $customer->full_name }}</td>
                <td>{{ $customer->date_of_birth }}</td>
                <td>{{ $customer->user->email ?? 'N/A' }}</td>
                <td>{{ $customer->gender }}</td>
                <td>
                    <a href="{{ route('backend.customer.edit', $customer->customer_id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('backend.customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $customers->links() }}
    </div>
</div>

</body>
</html>
