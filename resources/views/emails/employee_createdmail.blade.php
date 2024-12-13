<!DOCTYPE html>
<html>

<head>
    <title>Thông tin tài khoản</title>
</head>

<body>
    <h1>Xin chào {{ $employee->full_name }}</h1>
    <p>Chúng tôi đã tạo tài khoản cho bạn trên hệ thống.</p>
    <p>Thông tin đăng nhập:</p>
    <ul>
        <li><strong>Tên tài khoản:</strong> {{ $username }}</li>
        <li><strong>Mật khẩu:</strong> {{ $password }}</li>
    </ul>
    <p>Vui lòng đăng nhập và đổi mật khẩu để bảo mật tài khoản.</p>
    <p>Hoặc ấn vào đường dẫn này để thay đổi mật khẩu của bạn ngay (<a href="{{route('changePassEmail',$user_id)}}">link thay đổi mật khẩu</a>)</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ quản lý</p>
</body>

</html>