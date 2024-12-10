<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        a.btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            margin-bottom: 20px;
            color: #fff;
            background-color: #6c757d;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        a.btn:hover {
            background-color: #5a6268;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #4caf50;
            color: white;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px; /* Khoảng cách giữa các nút */
        }

        .action-buttons form {
            display: inline-block;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        button[type="submit"] {
            color: white;
        }

        button[type="submit"]:hover {
            opacity: 0.9;
        }

        button[style*="blue"] {
            background-color: #007bff;
        }

        button[style*="red"] {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<h1>Danh sách khách hàng chưa duyệt</h1>
<a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>
@if (session('success'))
    <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
@endif
<table border="1">
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
                                <button type="submit" style="background-color: blue;">Phê duyệt</button>
                            </form>
                            <form action="{{ route('customer.reject', ['customer_id' => $customer->customer_id]) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: red;">Không duyệt</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
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
