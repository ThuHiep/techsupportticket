<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách chờ phê duyệt</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Danh sách khách hàng chờ duyệt</h1>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Tài khoản</th>
                <th>Mật khẩu</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->user->username }}</td>
                    <td>{{ $customer->user->password }}</td>
                    <td>
                        <button onclick="approveCustomer({{ $customer->customer_id }})" style="color:green">Phê duyệt</button>
                        <button onclick="disapproveUser({{ $customer->customer_id }})" style="color:red">Không duyệt</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function approveCustomer(customerId) {
            axios.post(`/customer/${customerId}/approve`)
                .then(response => {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Phê duyệt thành công!',
                            text: 'Khách hàng đã được phê duyệt.',
                        });
                        location.reload(); // Tải lại trang
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
            alert(`Đã không duyệt người dùng ID: ${userId}`);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

