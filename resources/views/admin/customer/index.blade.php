<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style.css') }}">
    <style>
        /* Khi sidebar ở trạng thái bình thường */
        body .container {
            width: calc(98%); /* Độ rộng sau khi trừ sidebar */
            transition: all 0.3s ease-in-out;
        }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%); /* Mở rộng nội dung khi sidebar thu nhỏ */
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="wrapper wrapper-content">
        <div id="notification" style="display: none; position: fixed; top: 10px; right: 10px; background-color: #28a745; color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
            <span id="notification-message"></span>
        </div>
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
                <a href="{{ route('customer.pending') }}" class="show-users-btn">
                    Chờ duyệt
                    <span class="badge" id="userCount">0</span>
                </a>
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
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="8" style="text-align: center; color: red;">Không có kết quả tìm kiếm</td>
                        </tr>
                    @else
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
                                    @if ($customer->status === 'active')
                                        <span style="color:green; font-size: 40px; margin-right: 2px; vertical-align: middle;">&#8226;</span>
                                        <span style="vertical-align: middle;">Đang hoạt động</span>
                                    @endif
                                </td>
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
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
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
                const userCount = data.length; // Lấy số lượng khách hàng chờ duyệt
                document.getElementById('userCount').textContent = userCount;
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng khách hàng:', error));
    }

    function showNotification(message, backgroundColor) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');

        // Cập nhật nội dung và màu nền
        notificationMessage.textContent = message;
        notification.style.backgroundColor = backgroundColor;

        // Hiển thị thông báo
        notification.style.display = 'block';

        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
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
