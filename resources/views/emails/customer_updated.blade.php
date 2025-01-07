<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo cập nhật thông tin</title>
</head>
<body>
<h1>Thông báo cập nhật thông tin</h1>
<p>Chào {{ $customer->full_name }},</p>
<p>Thông tin của bạn đã được cập nhật thành công. Dưới đây là tất cả thông tin của bạn:</p>

<ul>
    <li><strong>Tên:</strong> {{ $customer->full_name }}</li>
    <li><strong>Ngày sinh:</strong> {{ $customer->date_of_birth }}</li>
    <li><strong>Giới tính:</strong> {{ $customer->gender }}</li>
    <li><strong>Số điện thoại:</strong> {{ $customer->phone }}</li>
    <li><strong>Địa chỉ:</strong> {{ $customer->address }}</li>
    <li><strong>Software:</strong> {{ $customer->software }}</li>
    <li><strong>Website:</strong> {{ $customer->website }}</li>
    <li><strong>Công ty:</strong> {{ $customer->company }}</li>
    <li><strong>Email:</strong> {{ $customer->email }}</li>
    <li><strong>Mã số thuế:</strong> {{ $customer->tax_id }}</li>
</ul>

<p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
</body>
</html>
