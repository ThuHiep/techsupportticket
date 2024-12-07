<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .report-section {
            margin: 20px 0;
        }
        .report-section h3 {
            margin-bottom: 15px;
        }
        .btn-filter {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center my-4">Báo cáo</h1>

    <!-- Báo cáo theo khách hàng -->
    <div class="report-section">
        <h3>Báo cáo theo khách hàng</h3>
        <p>Báo cáo này tổng hợp số lượng yêu cầu hỗ trợ kỹ thuật của từng khách hàng. Việc này giúp nhận diện khách hàng có nhiều yêu cầu cần hỗ trợ và theo dõi lịch sử yêu cầu của họ.</p>
        <button class="btn btn-primary">Hiển thị báo cáo</button>
        <div class="btn-filter">
            <button class="btn btn-secondary">Lọc theo thời gian</button>
            <button class="btn btn-secondary">Lọc theo trạng thái</button>
        </div>
    </div>

    <!-- Báo cáo theo loại yêu cầu -->
    <div class="report-section">
        <h3>Báo cáo theo loại yêu cầu</h3>
        <p>Báo cáo này sẽ cung cấp thông tin về số lượng yêu cầu hỗ trợ theo từng loại yêu cầu (ví dụ: vấn đề kỹ thuật, thanh toán, vấn đề về hạ tầng, ...).</p>
        <button class="btn btn-primary">Hiển thị báo cáo</button>
        <div class="btn-filter">
            <button class="btn btn-secondary">Lọc theo thời gian</button>
            <button class="btn btn-secondary">Lọc theo trạng thái</button>
        </div>
    </div>

    <!-- Báo cáo theo phòng ban -->
    <div class="report-section">
        <h3>Báo cáo theo phòng ban</h3>
        <p>Báo cáo này giúp quản lý số lượng yêu cầu hỗ trợ được xử lý bởi từng phòng ban (ví dụ: phòng kỹ thuật, phòng chăm sóc khách hàng, phòng kế toán, v.v.).</p>
        <button class="btn btn-primary">Hiển thị báo cáo</button>
        <div class="btn-filter">
            <button class="btn btn-secondary">Lọc theo thời gian</button>
            <button class="btn btn-secondary">Lọc theo trạng thái</button>
            <button class="btn btn-secondary">Lọc theo phòng ban</button>
        </div>
    </div>

    <!-- Báo cáo theo thời gian -->
    <div class="report-section">
        <h3>Báo cáo theo thời gian</h3>
        <p>Báo cáo này giúp theo dõi số lượng yêu cầu hỗ trợ theo từng khoảng thời gian (ví dụ: theo ngày, tuần, tháng, quý, năm).</p>
        <button class="btn btn-primary">Hiển thị báo cáo</button>
        <div class="btn-filter">
            <button class="btn btn-secondary">Lọc theo phòng ban</button>
            <button class="btn btn-secondary">Lọc theo trạng thái</button>
            <button class="btn btn-secondary">Lọc theo loại yêu cầu</button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
