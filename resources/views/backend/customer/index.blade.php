<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        .container {
            width: 1200px;
            margin-top: 22px;
            margin-left: 12px;
            padding: 0 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            padding-bottom: 20px;
            color: #FF9700;
        }

        .top-bar {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-customer-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: auto; /* Đưa nút Thêm mới ra mép trái */
        }

        .add-customer-btn:hover {
            background-color: #45a049;
        }

        .search-container {
            display: flex;
            align-items: center;
            margin: 0 auto; /* Đưa thanh tìm kiếm vào giữa */
        }

        .search-container input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #FF9700;
            border-radius: 5px;
            outline: none;
        }

        .search-container input[type="text"]:focus {
            border-color: #FF9700;
        }

        .search-container button {
            padding: 8px 15px;
            background-color: #FF9700;
            color: white;
            border: 1px solid #FF9700;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #f57c00;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        td {
            background-color: #fff;
        }

        .button-group {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .button-group a, .button-group form button {
            padding: 8px 15px;
            background-color: #ff9800;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-group a:hover, .button-group form button:hover {
            background-color: #f57c00;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Danh sách khách hàng</h1>

    <!-- Nút Thêm mới và Thanh tìm kiếm cùng một hàng -->
    <div class="top-bar">
        <!-- Nút Thêm mới nằm bên trái -->
        <a href="{{ route('backend.customer.create') }}" class="add-customer-btn">Thêm mới</a>

        <!-- Thanh tìm kiếm nằm giữa -->
        <div class="search-container">
            <form action="{{ route('backend.customer.index') }}" method="GET">
                <input type="text" name="search" placeholder="Tìm kiếm khách hàng" value="{{ request()->query('search') }}">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Mã khách hàng</th>
                <th>Tên</th>
                <th>Hình ảnh</th>
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
                    <td>
                        <!-- Hiển thị ảnh nếu tồn tại -->
                        @if($customer->profile_image)
                            <img src="{{ asset('backend/img/customer/' . $customer->profile_image) }}" alt="Hình ảnh khách hàng" width="100" height="100">
                        @else
                            <img src="{{ asset('backend/img/gallery/1.jpg') }}" alt="Ảnh đại diện mặc định" width="100" height="100">
                        @endif
                    </td>
                    <td>{{ $customer->date_of_birth }}</td>
                    <td>{{ $customer->user->email ?? 'N/A' }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td >
                        <form action="{{ route('backend.customer.edit', $customer->customer_id) }}" style="display:inline;">
                            <button type="submit">Sửa</button>
                        </form>
                        <form action="{{ route('backend.customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        {{ $customers->links() }}
    </div>
</div>
</body>
</html>
