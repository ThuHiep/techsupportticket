<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<h1>Danh sách khách hàng chưa duyệt</h1>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@elseif (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table border="1">
    <thead>
    <tr>
        <th>STT</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Thao tác</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($customers as $index => $customer)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $customer->full_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>
                <form action="{{ route('customer.approve', ['customer_id' => $customer->customer_id]) }}" method="POST">
                    @csrf
                    <button type="submit">Phê duyệt</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
