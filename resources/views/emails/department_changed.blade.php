<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu hỗ trợ thay đổi phòng ban</title>
</head>
<body>
<p>Xin chào {{ $supportRequest->customer->full_name }},</p>

<p>
    Yêu cầu hỗ trợ có mã <strong>{{ $supportRequest->request_id }}</strong>
    đã được chuyển sang phòng ban <strong>{{ $newDepartmentName }}</strong>.
</p>

<p>Tiêu đề yêu cầu: <strong>{{ $supportRequest->subject }}</strong></p>
<p>Trạng thái hiện tại: <strong>{{ $supportRequest->status }}</strong></p>

<p>
    Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.<br>
    Xin cảm ơn!
</p>
</body>
</html>
