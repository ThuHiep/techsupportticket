<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo cập nhật thông tin</title>
</head>

<body>
    <h1>Thông báo cập nhật thông tin</h1>
    <p>Chào {{ $employee->full_name }},</p>
    <p>Thành viên của bạn đã được cập nhật thành công. Dưới đây là thông tin của bạn:</p>

    <ul>
        <li><strong>Tên:</strong> {{ $employee->full_name }}</li>
        <li><strong>Ngày sinh:</strong> {{ $employee->date_of_birth }}</li>
        <li><strong>Giới tính:</strong> {{ $employee->gender }}</li>
        <li><strong>Số điện thoại:</strong> {{ $employee->phone }}</li>
        <li><strong>Địa chỉ:</strong> {{ $employee->address }}</li>
        <li><strong>Email:</strong> {{ $employee->email }}</li>
    </ul>

    <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
</body>

</html>