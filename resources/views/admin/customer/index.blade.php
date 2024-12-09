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
        transform: translate(-50%, -50%); /* Căn giữa modal */
        width: 50%; /* Bạn có thể thay đổi kích thước của modal nếu muốn */
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000; /* Đảm bảo modal không bị che khuất */
        display: none; /* Ẩn modal mặc định */
        opacity: 0; /* Ẩn modal */
        transition: opacity 0.3s ease-in-out;
    }

    /* Hiển thị modal */
    .modal.show {
        display: block;
        opacity: 1; /* Khi modal hiển thị, độ mờ sẽ trở thành 1 */
    }

    /* Nền mờ phía sau modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5); /* Màu nền mờ */
        z-index: 999; /* Đảm bảo nền mờ ở phía dưới modal */
        display: none;
    }

    /* Hiển thị nền mờ */
    .modal-overlay.show {
        display: block;
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
    <div id="usersModal" class="modal">
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
        const overlay = document.createElement('div');
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
                            <button onclick="approveUser(${user.id})" style="color:green">Phê duyệt</button>
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

    function closeUsersModal() {
        const modal = document.getElementById('usersModal');
        const overlay = document.querySelector('.modal-overlay');
        modal.classList.remove('show'); // Ẩn modal
        if (overlay) overlay.remove(); // Xóa lớp nền mờ
    }

    // Hàm phê duyệt người dùng
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
                    Swal.fire({
                        title: 'Phê duyệt thành công!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        updateUserCount(); // Cập nhật số lượng người dùng
                        showUsersModal(); // Cập nhật danh sách modal
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi phê duyệt!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>
</html>
{{--    <!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Danh sách khách hàng</title>--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>Danh sách khách hàng chưa duyệt</h1>--}}

{{--@if (session('success'))--}}
{{--    <div class="alert alert-success">{{ session('success') }}</div>--}}
{{--@elseif (session('error'))--}}
{{--    <div class="alert alert-danger">{{ session('error') }}</div>--}}
{{--@endif--}}

{{--<table border="1">--}}
{{--    <thead>--}}
{{--    <tr>--}}
{{--        <th>STT</th>--}}
{{--        <th>Mã khách hàng</th>--}}
{{--        <th>Họ tên</th>--}}
{{--        <th>Email</th>--}}
{{--        <th>Thao tác</th>--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    @forelse ($customers as $index => $customer)--}}
{{--        <tr>--}}
{{--            <td>{{ $index + 1 }}</td>--}}
{{--            <td>{{ $customer->customer_id }}</td>--}}
{{--            <td>{{ $customer->full_name }}</td>--}}
{{--            <td>{{ $customer->email }}</td>--}}
{{--            <td>--}}
{{--                <!-- Form phê duyệt khách hàng -->--}}
{{--                <form action="{{ route('customer.approve', $customer->customer_id) }}" method="GET" enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    <button type="submit">Phê duyệt</button>--}}
{{--                </form>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    @empty--}}
{{--        <tr>--}}
{{--            <td colspan="5">Không có khách hàng nào cần phê duyệt.</td>--}}
{{--        </tr>--}}
{{--    @endforelse--}}
{{--    </tbody>--}}
{{--</table>--}}

{{--<script>--}}
{{--    function approveCustomer(customerId) {--}}
{{--        axios.post(`/customer/${customerId}/approve`, {}, {--}}
{{--            headers: {--}}
{{--                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token--}}
{{--            }--}}
{{--        })--}}
{{--            .then(response => {--}}
{{--                if (response.data.status === 'success') {--}}
{{--                    Swal.fire('Phê duyệt', response.data.message, 'success');--}}
{{--                    const row = document.querySelector(`button[onclick="approveCustomer(${customerId})"]`).closest('tr');--}}
{{--                    if (row) row.remove();--}}
{{--                } else {--}}
{{--                    Swal.fire('Lỗi', response.data.message, 'error');--}}
{{--                }--}}
{{--            })--}}
{{--            .catch(error => {--}}
{{--                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi phê duyệt: ' + error.message, 'error');--}}
{{--                console.error('Error:', error);--}}
{{--            });--}}
{{--    }--}}
{{--</script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}
{{--</body>--}}
{{--</html>--}}
