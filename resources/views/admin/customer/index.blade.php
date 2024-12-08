<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style.css') }}">

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
    <div class="table-container">
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
                    <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $index + 1 }}</td> <!-- STT -->
                    
                    <td>{{ $customer->full_name }}</td>
                    <td>
                        @if($customer->profile_image)
                            <img src="{{ asset('admin/img/customer/' . $customer->profile_image) }}" alt="Hình ảnh khách hàng" class="customer-image">
                        @else
                            <img src="{{ asset('admin/img/gallery/') }}" alt="Ảnh đại diện mặc định" class="customer-image">
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
    <div class="pagination">
        {{ $customers->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        updateUserCount(); // Cập nhật số lượng ngay khi tải trang
    });

    function updateUserCount() {
        fetch('{{ route("admin.user.list") }}')
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
                        <td>${user.username}</td>
                        <td>${user.password}</td>
                        <td>
                            <button onclick="approveUser(${user.id})" style="color:green">Phê duyệt</button>
                            <button onclick="disapproveUser(${user.id})" style="color:red">Không duyệt</button>
                        </td>
                    </tr>
                `;
                });
            })
            .catch(error => console.error('Lỗi khi tải dữ liệu người dùng:', error));
    }

    function closeUsersModal() {
        const modal = document.getElementById('usersModal');
        modal.classList.add('hidden'); // Ẩn modal
    }

    function approveUser(userId) {
        fetch('{{ route("customer.approve", ":id") }}'.replace(':id', userId), {
            method: 'POST', // Sử dụng phương thức POST
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token CSRF
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Phê duyệt', data.message, 'success');
                    updateUserCount(); // Cập nhật số lượng
                    showUsersModal(); // Cập nhật danh sách modal
                } else {
                    Swal.fire('Lỗi', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi phê duyệt!', 'error');
                console.error('Error:', error);
            });
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
