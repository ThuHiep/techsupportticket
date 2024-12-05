<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0z4OJyKoBRb6QUqUJ0hB2ihYyELRJ0rokw7DtNUE6c7z8k" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/css/customer/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sử dụng SweetAlert2 để xác nhận xóa
        function showDeleteModal(event, formId) {
            event.preventDefault(); // Ngừng gửi form ngay lập tức

            // Hiển thị SweetAlert2 xác nhận xóa
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa khách hàng này?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Nếu người dùng chọn "Xóa", gửi form để xóa khách hàng
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
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
                        <form action="{{ route('backend.customer.edit', $customer->customer_id) }}" style="display:inline;">
                            <button type="submit" class="edit-button">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </form>
                        <form action="{{ route('backend.customer.delete', $customer->customer_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $customer->customer_id }}">
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
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        {{ $customers->links() }}
    </div>
</div>

</body>
</html>
