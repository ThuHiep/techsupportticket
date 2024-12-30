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
        
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: left">Danh sách khách hàng chưa duyệt</h1>
    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Quay lại</a>

    <form action="{{ route('customer.pending') }}" method="GET">
        <div class="search-container">
            <div style="position: relative;">
                <input type="text" id="searchName" name="name" placeholder="Nhập tên khách hàng cần tìm" value="{{ request('name') }}" />
                @if($searchPerformed)
                <a
                    href="{{ route('customer.pending') }}"
                    id="clearButton"
                    style="position: absolute; right: 3%; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">
                    ✖
                </a>
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
            @if ($searchName && !$searchDate)
                @if ($totalResults > 0)

                <div id="search-notification"  class="alert-success" style="text-align: center; color: green; margin-top: 10px;">
                        Tìm thấy {{ $totalResults }} tài khoản chưa duyệt có tên "{{ $searchName }}"
                    </div>
                @else
                    <div id="search-notification" class="alert-danger" style="text-align: center; color: red; margin-top: 10px;">
                        Không tìm thấy tài khoản chưa duyệt có tên "{{ $searchName }}"
                    </div>
                @endif
            @elseif ($searchDate && !$searchName)
                @php
                    $formattedDate = \Carbon\Carbon::parse($searchDate)->format('d/m/Y');
                @endphp
                @if ($totalResults > 0)
                    <div id="search-notification" class="alert alert-success">
                        Tìm thấy {{ $totalResults }} tài khoản chưa duyệt vào ngày "{{ $formattedDate }}"
                    </div>
                @else
                    <div id="search-notification" class="alert alert-danger" style="text-align: center; margin-top: 10px;">
                        Không tìm thấy tài khoản chưa duyệt vào ngày "{{ $formattedDate }}"
                    </div>
                @endif
            @elseif ($searchName && $searchDate)
                @php
                    $formattedDate = \Carbon\Carbon::parse($searchDate)->format('d/m/Y');
                @endphp
                @if ($totalResults > 0)
                    <div id="search-notification" class="alert alert-success" style="text-align: center; margin-top: 10px;">
                        Tìm thấy {{ $totalResults }} tài khoản có tên "{{ $searchName }}" vào ngày "{{ $formattedDate }}"
                    </div>
                @else
                    <div id="search-notification" class="alert alert-danger" style="text-align: center; margin-top: 10px;">
                        Không tìm thấy tài khoản có tên "{{ $searchName }}" vào ngày "{{ $formattedDate }}"
                    </div>
                @endif
            @endif
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
                                <form action="{{ route('customer.approve', ['customer_id' => $customer->customer_id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="green"><i class="fas fa-check-circle fa-lg"></i></button>
                                </form>
                                <form action="{{ route('customer.reject', ['customer_id' => $customer->customer_id]) }}" method="POST">
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
    // Tự động ẩn thông báo tìm kiếm sau 3 giây
setTimeout(function() {
    var searchNotification = document.getElementById('search-notification');
    if (searchNotification) {
        searchNotification.style.transition = 'opacity 0.5s ease-out';
        searchNotification.style.opacity = '0';
        setTimeout(() => searchNotification.style.display = 'none', 500); // Ẩn hoàn toàn sau hiệu ứng mờ dần
    }
}, 3000); // Thời gian 3 giây

</script>
<script>
    // Set duration to 5000ms (5 seconds)
    const duration = 5000;
    // Check for success alert
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.remove();
        }, duration);
    }
    // Check for error alert
    const errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        setTimeout(() => {
            errorAlert.remove();
        }, duration);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
