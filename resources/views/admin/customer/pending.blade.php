<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/customer/pending.css') }}">
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none; /* Mặc định ẩn */
            align-items: center;
            justify-content: center;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="loading-overlay" id="loading-overlay">
    <div class="spinner"></div>
</div>
<div class="container">
    <h1 style="text-align: left">Danh sách khách hàng chưa duyệt</h1>
    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>

    <form action="{{ route('customer.pending') }}" method="GET">
        <div class="search-container">
            <div style="position: relative;">
                <input type="text" id="searchName" name="name" placeholder="Nhập tên khách hàng cần tìm" value="{{ request('name') }}" />
                @if($searchPerformed)
                    <a href="{{ route('customer.pending') }}" id="clearButton" style="position: absolute; right: 3%; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">✖</a>
                @endif
            </div>
            <input type="date" id="searchDate" name="date" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}" />
            <button type="submit">Tìm kiếm</button>
        </div>
    </form>

    <div class="table-container">
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
        @endif
        @if ($searchPerformed)
            <div id="search-notification" class="alert {{ $totalResults > 0 ? 'alert-success' : 'alert-danger' }}" style="text-align: center; margin-top: 10px;">
                {{ $totalResults > 0 ? 'Tìm thấy ' . $totalResults . ' tài khoản' : 'Không tìm thấy tài khoản' }}
            </div>
        @endif
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Tên tài khoản</th>
                <th>Ngày đăng ký</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @if ($customers->isEmpty())
                <tr>
                    <td colspan="6" style="text-align: center; font-weight: bold;">Không có khách hàng cần duyệt</td>
                </tr>
            @else
                @foreach ($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->full_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->user->username }}</td>
                        <td>{{ \Carbon\Carbon::parse($customer->create_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('customer.approve', ['customer_id' => $customer->customer_id]) }}" method="POST" class="approve-form">
                                    @csrf
                                    <button type="submit" class="green"><i class="fas fa-check-circle fa-lg"></i></button>
                                </form>
                                <form action="{{ route('customer.reject', ['customer_id' => $customer->customer_id]) }}" method="POST" class="reject-form">
                                    @csrf
                                    <button type="submit" class="red"><i class="fas fa-times-circle fa-lg"></i></button>
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
    document.addEventListener('DOMContentLoaded', function () {
        const loadingOverlay = document.getElementById('loading-overlay');

        document.querySelectorAll('.approve-form button[type="submit"], .reject-form button[type="submit"]').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Ngăn chặn hành vi submit mặc định
                loadingOverlay.style.display = 'flex'; // Hiển thị spinner
                const form = button.closest('form'); // Lấy form tương ứng
                setTimeout(() => form.submit(), 100); // Chờ một chút rồi submit form
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
