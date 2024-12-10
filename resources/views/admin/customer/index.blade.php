<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style.css') }}">
    <style>
        /* Modal cơ bản */
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        /* Hiển thị modal */
        .modal.show {
            display: block;
            opacity: 1;
        }

        /* Nền mờ phía sau modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        /* Hiển thị nền mờ */
        .modal-overlay.show {
            display: block;
        }

        .show-users-btn {
            float: right; /* Đưa nút sang phía bên phải */
            margin: 10px 0; /* Thêm khoảng cách ở trên và dưới */
            color: white; /* Màu chữ */
            border: none; /* Bỏ viền */
            border-radius: 5px; /* Bo góc */
            cursor: pointer; /* Con trỏ chuột khi hover */
            font-size: 16px; /* Kích thước chữ */
        }
        /* Giảm kích thước modal */
        .modal {
            width: 30%; /* Giảm chiều rộng xuống 30% */
            max-width: 660px; /* Giới hạn chiều rộng tối đa */
            height: auto; /* Tự động điều chỉnh chiều cao */
            padding: 20px; /* Thêm khoảng cách bên trong */
            border-radius: 10px; /* Bo góc cho hộp */
        }

        /* Giảm kích thước nội dung bên trong */
        .modal-content {
            font-size: 14px; /* Thu nhỏ kích thước chữ */
            line-height: 1.5; /* Tăng khoảng cách giữa các dòng */
        }

        /* Thu nhỏ bảng trong modal */
        .user-table {
            width: 100%; /* Bảng chiếm toàn bộ chiều ngang của modal */
            font-size: 13px; /* Kích thước chữ nhỏ hơn */
            border-collapse: collapse; /* Loại bỏ khoảng cách giữa các đường kẻ */
        }

        .user-table th, .user-table td {
            padding: 5px 10px; /* Thu nhỏ khoảng cách trong ô */
            text-align: left; /* Canh trái nội dung */
        }

        .user-table th {
            background-color: #f4f4f4; /* Màu nền cho tiêu đề bảng */
        }

        /* Chỉnh nút trong modal */
        .modal button {
            font-size: 13px; /* Thu nhỏ chữ của nút */
            padding: 5px 10px; /* Thu nhỏ kích thước nút */
            margin: 5px 0; /* Thêm khoảng cách giữa các nút */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Danh sách khách hàng</h1>
    <button class="show-users-btn" onclick="showUsersModal()">
        Chờ duyệt
        <span class="badge" id="userCount">0</span>
    </button>
    <a href="{{ route('customer.create') }}" class="add-customer-btn">Thêm mới</a>
    <div class="search-container">
        <form action="{{ route('customer.index') }}" method="GET">
            <input type="text" name="search" placeholder="Nhập tên khách hàng cần tìm" value="{{ request()->query('search') }}">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>
    <!-- Modal Chờ duyệt -->
    <div class="table-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Hình ảnh</th>
                <th>Ngày sinh</th>
                <th>Email</th>
                <th>Giới tính</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($customers as $index => $customer)
                <tr>
                    <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $index + 1 }}</td>
                    <td>{{ $customer->full_name }}</td>
                    <td>
                        @if($customer->profile_image)
                            <img src="{{ asset('admin/img/customer/' . $customer->profile_image) }}" alt="Hình ảnh khách hàng" class="customer-image">
                        @else
                            <img src="{{ asset('admin/img/gallery/') }}" alt="Ảnh đại diện " class="customer-image">
                        @endif
                    </td>
                    <td>{{ $customer->date_of_birth }}</td>
                    <td>{{ $customer->user->email ?? 'N/A' }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td>
                        <form action="{{ route('customer.edit', $customer->customer_id) }}" style="display:inline;">
                            <button type="submit" class="edit-button">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>
                        <form action="{{ route('customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $customer->customer_id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $customer->customer_id }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $customers->links('pagination::bootstrap-4') }}
    </div>
    <!----------->
    <div id="usersModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUsersModal()">&times;</span>
            <h2>Danh sách khách hàng chờ duyệt</h2>
            <table class="user-table">
                <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody id="userTableBody">
                @foreach ($pendingCustomers as $customer)
                    <tr>
                        <td>{{ $customer->full_name }}</td>
                        <td>{{ $customer->user->email ?? 'N/A' }}</td>
                        <td>
                            <button onclick="approveCustomer({{ $customer->customer_id }})" style="color:green">Phê duyệt</button>
                            <button onclick="disapproveUser({{ $customer->customer_id }})" style="color:red">Không duyệt</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeUsersModal();
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
        updateUserCount(); // Cập nhật số lượng ngay khi tải trang
    });

    function updateUserCount() {
        fetch('{{ route("admin.user.list") }}')
            .then(response => response.json())
            .then(data => {
                const userCount = data.length; // Lấy số lượng khách hàng chờ duyệt
                document.getElementById('userCount').textContent = userCount;
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng khách hàng:', error));
    }
    // Hiển thị modal danh sách khách hàng chờ duyệt
    function showUsersModal() {
        const modal = document.getElementById('usersModal');
        const overlay = document.createElement('div'); // Tạo lớp nền mờ
        overlay.classList.add('modal-overlay');
        document.body.appendChild(overlay); // Thêm lớp nền mờ vào body

        modal.classList.add('show'); // Thêm lớp show vào modal

        // Hiển thị danh sách người dùng qua AJAX
        fetch('{{ route("admin.user.list") }}')
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
                <td>${user.full_name}</td>
                <td>${user.phone}</td>
                <td>
                    <button onclick="approveCustomer(${user.id})" style="color:green">Phê duyệt</button>
                    <button onclick="disapproveUser(${user.id})" style="color:red">Không duyệt</button>
                </td>
            </tr>
        `;
                });
            })
            .catch(error => console.error('Lỗi khi tải dữ liệu người dùng:', error));
    }

    // Đóng modal khi nhấn vào lớp nền mờ
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeUsersModal();
        }
    });

    // Đảm bảo việc phân trang không bị gián đoạn khi modal được đóng
    function closeUsersModal() {
        const modal = document.getElementById('usersModal');
        const overlay = document.querySelector('.modal-overlay');
        modal.classList.remove('show'); // Ẩn modal
        if (overlay) overlay.remove(); // Xóa lớp nền mờ
        // Cập nhật lại số lượng khách hàng chờ duyệt khi đóng modal
        updateUserCount();
    }



    // Hàm phê duyệt người dùng
    function approveCustomer(customerId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        axios.post(`/customer/${customerId}/approve`, {}, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        })
            .then(response => {
                if (response.data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Phê duyệt thành công!',
                        text: 'Khách hàng đã được phê duyệt.',
                    });

                    // Xóa dòng khách hàng khỏi danh sách
                    const row = document.querySelector(`button[onclick="approveCustomer(${customerId})"]`).closest('tr');
                    if (row) row.remove();

                    // Cập nhật lại số lượng khách hàng chờ duyệt
                    updateUserCount();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: response.data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Đã xảy ra lỗi khi phê duyệt khách hàng.',
                });
                console.error('Error:', error);
            });
    }

    function disapproveUser(userId) {
        alert(`Đã không duyệt người dùng ID: ${userId}`); // Thông báo khi không duyệt
    }

    function showDeleteModal(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        if (confirm('Bạn có chắc chắn muốn xóa khách hàng này? Hành động này không thể hoàn tác!')) {
            document.getElementById(formId).submit(); // Thực hiện xóa nếu người dùng xác nhận
        }
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>
</html>
