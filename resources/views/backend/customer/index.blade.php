<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0z4OJyKoBRb6QUqUJ0hB2ihYyELRJ0rokw7DtNUE6c7z8k" crossorigin="anonymous">
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
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: auto;
        }

        .add-customer-btn:hover {
            background-color: #45a049;
        }

        .search-container {
            display: flex;
            align-items: center;
            margin-right: 350px;
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

        /* Hình ảnh khách hàng - Giảm kích thước */
        .customer-image {
            width: 70px; /* Điều chỉnh kích thước nhỏ hơn cho hình ảnh */
            height: 70px; /* Điều chỉnh kích thước nhỏ hơn cho hình ảnh */
            border-radius: 5px; /* Bo tròn các góc của hình ảnh */
        }
<<<<<<< HEAD
    
        /* Đặt hai nút Sửa và Xóa cùng hàng ngang và có khoảng cách giữa chúng */
=======

>>>>>>> a5a0458b635f881e034cfceb1ec72e0bca5e8f94
        .button-group {
            display: flex;
            align-items: center;
            gap: 10px; /* Khoảng cách giữa hai nút */
        }

        /* Nút Sửa - màu xanh */
        .edit-button {
            padding: 10px 15px;
            background-color: #007bff; /* Màu xanh cho nút Sửa */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px; /* Khoảng cách giữa biểu tượng và chữ */
        }

        .edit-button:hover {
            background-color: #0056b3; /* Màu xanh đậm hơn khi hover */
        }

        /* Nút Xóa - màu đỏ */
        .delete-button {
            padding: 10px 15px;
            background-color: #ff0000; /* Màu đỏ cho nút Xóa */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px; /* Khoảng cách giữa biểu tượng và chữ */
        }

        .delete-button:hover {
            background-color: #d32f2f; /* Màu đỏ đậm hơn khi hover */
        }
<<<<<<< HEAD
    
        /* Tăng kích thước biểu tượng */
        .edit-button i,
        .delete-button i {
            font-size: 1.5em; /* Tăng kích thước biểu tượng */
        }
    
=======

>>>>>>> a5a0458b635f881e034cfceb1ec72e0bca5e8f94
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
<<<<<<< HEAD
    
    
    
    
    
=======



>>>>>>> a5a0458b635f881e034cfceb1ec72e0bca5e8f94
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
                <input type="text" name="search" placeholder="Nhập tên khách hàng cần tìm" value="{{ request()->query('search') }}">
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
                <th>Họ tên</th>
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
                            <img src="{{ asset('backend/img/customer/' . $customer->profile_image) }}" alt="Hình ảnh khách hàng" class="customer-image">
                        @else
                            <img src="{{ asset('backend/img/gallery/') }}" alt="Ảnh đại diện mặc định" class="customer-image">
                        @endif
                    </td>

                    <td>{{ $customer->date_of_birth }}</td>
                    <td>{{ $customer->user->email ?? 'N/A' }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td>
                        <!-- Container chứa nút Sửa và Xóa -->
                        <div class="button-group">
                            <!-- Nút Sửa -->
                            <form action="{{ route('backend.customer.edit', $customer->customer_id) }}" style="display:inline;">
                                <button type="submit" class="edit-button">
                                    <i class="fas fa-edit"></i> 
                                </button>
                            </form>
                            
                            <!-- Nút Xóa -->
                            <form action="{{ route('backend.customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
<<<<<<< HEAD
                    
                    
                    
                    
=======


>>>>>>> a5a0458b635f881e034cfceb1ec72e0bca5e8f94
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
