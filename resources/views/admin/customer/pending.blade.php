<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/pending.css') }}">
    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: left">Danh sách khách hàng chưa duyệt</h1>
    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>
    <div class="table-container">
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
        @endif
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
            @if ($customers->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center; font-weight: bold;">Không có khách hàng cần duyệt</td>
                </tr>
            @else
                @foreach ($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->full_name }}</td>
                        <td>{{ $customer->user->username }}</td>
                        <td>{{ $customer->user->password }}</td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('customer.approve', ['customer_id' => $customer->customer_id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="green">Phê duyệt</button>
                                </form>
                                <form action="{{ route('customer.reject', ['customer_id' => $customer->customer_id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="red">Không duyệt</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

</div>

<script>
    const duration = {{ session('notification_duration', 500) }};
    setTimeout(() => {
        document.getElementById('success-alert')?.remove();
        document.getElementById('error-alert')?.remove();
    }, duration);
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
