<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/css/customer/style.css') }}">
    <style>
        
body {
    font-family: open sans, Helvetica Neue, Helvetica, Arial, sans-serif;
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
    background-color: #FF9700;
    color: white;
    border: 1px solid #FF9700;
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

.button-group {
    display: flex;
    justify-content: space-around;
    margin-top: 10px;
}

/* Nút Sửa - màu xanh */
.edit-button {
    padding: 8px 15px;
    background-color: #007bff; /* Màu xanh cho nút Sửa */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.edit-button:hover {
    background-color: #0056b3; /* Màu xanh đậm hơn khi hover */
}

/* Nút Xóa - màu đỏ */
.delete-button {
    padding: 8px 15px;
    background-color: #ff0000; /* Màu đỏ cho nút Xóa */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.delete-button:hover {
    background-color: #d32f2f; /* Màu đỏ đậm hơn khi hover */
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
    <div class="top-bar">
        <a href="{{ route('customer.create') }}" class="add-customer-btn">Thêm mới</a>
        <div class="search-container">
            <form action="{{ route('customer.index') }}" method="GET">
                <input type="text" name="search" placeholder="Nhập tên khách hàng cần tìm" value="{{ request()->query('search') }}">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
        <button class="show-users-btn" onclick="showUsersModal()">
            Chờ duyệt
            <span class="badge" id="userCount">0</span>
        </button>
    </div>

    <table class="table table-striped">
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
                    <form action="{{ route('customer.edit', $customer->customer_id) }}" style="display:inline;">
                        <button type="submit" class="edit-button">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                    </form>
                    <form action="{{ route('customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $customer->customer_id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $customer->customer_id }}')">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div id="usersModal" class="modal hidden">
        <div class="modal-content">
            <span class="close" onclick="closeUsersModal()">&times;</span>
            <h2>Danh sách người dùng</h2>
            <table class="user-table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody id="userTableBody">
                <!-- Dữ liệu sẽ được tải bằng AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        updateUserCount(); // Cập nhật số lượng ngay khi tải trang
    });

    function updateUserCount() {
        fetch('{{ route("backend.user.list") }}')
            .then(response => response.json())
            .then(data => {
                const userCount = data.length; // Lấy số lượng người dùng từ dữ liệu
                document.getElementById('userCount').textContent = userCount;
            });
    }
    function showUsersModal() {
        const modal = document.getElementById('usersModal');
        modal.classList.remove('hidden'); // Hiển thị modal

        // Tải danh sách người dùng qua AJAX
        fetch('{{ route("backend.user.list") }}')
            .then(response => response.json())
            .then(data => {
                const userTableBody = document.getElementById('userTableBody');
                userTableBody.innerHTML = '';

                // Cập nhật số lượng
                document.getElementById('userCount').textContent = data.length;

                // Tạo hàng trong bảng
                data.forEach(user => {
                    userTableBody.innerHTML += `
                    <tr>
                        <td>${user.username}</td>
                        <td>${user.password}</td>
                        <td>
                            <button onclick="approveUser(${user.id})" style="color:green">Phê duyệt</button>
                            <button onclick="disapproveUser(${user.id})" style="color:red">Không duyệt</button>
                        </td>
                    </tr>
                `;
                });
            });
    }
    function closeUsersModal() {
        const modal = document.getElementById('usersModal');
        modal.classList.add('hidden'); // Ẩn modal
    }

    function approveUser(userId) {
        Swal.fire('Phê duyệt', `Đã phê duyệt người dùng ID: ${userId}`, 'success');
    }

    function disapproveUser(userId) {
        Swal.fire('Không duyệt', `Đã không duyệt người dùng ID: ${userId}`, 'error');
    }
    function showDeleteModal(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa khách hàng này?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit(); // Thực hiện xóa nếu người dùng xác nhận
            }
        });
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
