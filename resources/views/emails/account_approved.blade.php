<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản đã được duyệt</title>
</head>
<body>
<h1>Chúc mừng {{ $customerName }}</h1>
<p>Tài khoản của bạn đã được phê duyệt thành công!</p>
<p>Vui lòng đăng nhập và thay đổi mật khẩu trong vòng 24h tới.</p>
<p>Tên đăng nhập: <strong>{{ $username }}</strong></p>
<p>Mật khẩu: <strong>{{ $password }}</strong></p>
<p><a href="{{ url('/') }}">Website</a></p>
</body>
</html>
