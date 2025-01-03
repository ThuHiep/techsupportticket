<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kết quả xử lý yêu cầu</title>
</head>
<body>
<p>Xin chào {{ $supportRequest->customer->full_name }},</p>

<p>
    Yêu cầu của bạn đã được cập nhật như sau:
</p>
<ul>
    <li><strong>Mã yêu cầu:</strong> {{ $supportRequest->request_id }}</li>
    <li><strong>Tiêu đề:</strong> {{ $supportRequest->subject }}</li>
    <li><strong>Mô tả:</strong> {{ $supportRequest->description }}</li>
    <li><strong>Loại yêu cầu:</strong> {{ $requestTypeName }}</li>
    <li><strong>Phòng ban tiếp nhận:</strong> {{ $departmentName }}</li>
    @if($supportRequest->create_at)
        <li><strong>Thời gian tiếp nhận:</strong>
            {{ \Carbon\Carbon::parse($supportRequest->create_at)->format('d/m/Y H:i') }}
        </li>
    @endif
    <li><strong>Trạng thái:</strong> {{ $supportRequest->status }}</li>
</ul>

<p>
    Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Nếu có bất kỳ thắc mắc gì, bạn có thể liên hệ lại
    qua email này hoặc qua số điện thoại hỗ trợ của chúng tôi.
</p>

<p>Trân trọng,<br>
    TechSupportTicket Team</p>
</body>
</html>
