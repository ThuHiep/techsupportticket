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
<p>Vui lòng truy cập website của chúng tôi để trải nghiệm dịch vụ: <a href="{{ url('/') }}">Website</a></p>
</body>
</html>