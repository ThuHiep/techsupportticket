<!DOCTYPE html>
<html>

<head>
    <title>Mã xác thực</title>
</head>

<body>
<h1>Xin chào {{ $user->full_name }}</h1>
<p>Đây là mã xác thực của bạn</p>
<p><strong>Mã xác thực:</strong> {{ $otp }}</p>
<p><em>Mã xác thực sẽ hết hiệu lực trong vòng 5 phút.</em></p>
<p>Trân trọng,</p>
<p>Đội ngũ quản lý</p>
</body>

</html>
