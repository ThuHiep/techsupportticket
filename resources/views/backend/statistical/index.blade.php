<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .chart-container {
            width: 100%;
            max-width: 700px;
            margin: auto;
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
        <div class="chart-container">
            <canvas id="customerReport"></canvas>
        </div>
    </div>

    <!-- Báo cáo theo loại yêu cầu -->
    <div class="report-section">
        <h3>Báo cáo theo loại yêu cầu</h3>
        <p>Báo cáo này cung cấp thông tin về số lượng yêu cầu hỗ trợ theo từng loại yêu cầu.</p>
        <div class="chart-container">
            <canvas id="requestTypeReport"></canvas>
        </div>
    </div>

    <!-- Báo cáo theo phòng ban -->
    <div class="report-section">
        <h3>Báo cáo theo phòng ban</h3>
        <p>Báo cáo này giúp quản lý số lượng yêu cầu hỗ trợ được xử lý bởi từng phòng ban (ví dụ: phòng kỹ thuật, phòng chăm sóc khách hàng, phòng kế toán, v.v.).</p>
        <div class="chart-container">
            <canvas id="departmentReport"></canvas>
        </div>
    </div>

    <!-- Báo cáo theo thời gian -->
    <div class="report-section">
        <h3>Báo cáo theo thời gian</h3>
        <p>Báo cáo này giúp theo dõi số lượng yêu cầu hỗ trợ theo từng khoảng thời gian (ví dụ: theo ngày, tuần, tháng, quý, năm).</p>
        <div class="chart-container">
            <canvas id="timeReport"></canvas>
        </div>
    </div>
</div>

<script>
    // Báo cáo theo khách hàng
    const customerCtx = document.getElementById('customerReport').getContext('2d');
    new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: ['Khách hàng A', 'Khách hàng B', 'Khách hàng C'],
            datasets: [{
                label: 'Số yêu cầu',
                data: [12, 19, 7],
                backgroundColor: ['#3498db', '#1abc9c', '#9b59b6']
            }]
        }
    });

    // Báo cáo theo loại yêu cầu
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('requestTypeReport').getContext('2d');

        // Dữ liệu được truyền từ backend vào view
        const requestTypes = @json($requestTypes);

        if (requestTypes.length === 0) {
            console.error('No data available');
            return;
        }

        const labels = requestTypes.map((item) => item.request_type_name);
        const values = requestTypes.map((item) => item.count);

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Số lượng yêu cầu',
                        data: values,
                        backgroundColor: [
                            '#3498db',
                            '#1abc9c',
                            '#e74c3c',
                            '#f1c40f',
                            '#9b59b6',
                        ],
                    },
                ],
            },
        });
    });

    // Báo cáo theo phòng ban
    const departmentCtx = document.getElementById('departmentReport').getContext('2d');
    new Chart(departmentCtx, {
        type: 'line',
        data: {
            labels: ['Phòng kỹ thuật', 'CSKH', 'Phòng kế toán'],
            datasets: [{
                label: 'Số yêu cầu đã xử lý',
                data: [20, 10, 5],
                borderColor: '#2c3e50',
                backgroundColor: 'rgba(44, 62, 80, 0.5)',
                fill: true
            }]
        }
    });

    // Báo cáo theo thời gian
    const timeCtx = document.getElementById('timeReport').getContext('2d');
    new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4'],
            datasets: [{
                label: 'Số yêu cầu theo thời gian',
                data: [8, 15, 12, 10],
                borderColor: '#8e44ad',
                backgroundColor: 'rgba(142, 68, 173, 0.5)',
                fill: true
            }]
        }
    });
</script>
</body>
</html>
