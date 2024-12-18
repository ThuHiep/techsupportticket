<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/statistical/index.css') }}">
    <title>Báo cáo thống kê</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            font-size: 14px;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        .filter-container button {
            margin-right: 10px;
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .filter-container button:hover {
            background-color: #0056b3;
        }
        .active {
            background-color: orange; /* Màu cam cho nút đang được chọn */
            border-color: orange;
        }
    </style>
</head>
<body>
<div class="container">
    <div>
        <h1>Báo cáo thống kê theo loại yêu cầu</h1>
    </div>
    <div class="filter-container">
        <button id="btnToday" onclick="filterBy('today')">Hôm nay</button>
        <button id="btnMonthly" onclick="filterBy('monthly')">Tháng này</button>
        <button id="btnYearly" onclick="filterBy('yearly')">Năm này</button>
    </div>
    <div class="chart-container">
        <canvas id="requestTypeChart"></canvas>
    </div>
</div>

<script>
    // Dữ liệu ban đầu cho biểu đồ loại yêu cầu
    const initialData = {
        @foreach($requestTypes as $type)
        '{{ $type->request_type_name }}': {{ $type->requests_count }},
        @endforeach
    };

    // Biểu đồ loại yêu cầu
    const ctx = document.getElementById('requestTypeChart').getContext('2d');
    let requestTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(initialData),
            datasets: [{
                label: 'Số yêu cầu theo loại',
                data: Object.values(initialData),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.dataset.label}: ${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });

    // Hàm lọc theo thời gian
    function filterBy(period) {
        // Gọi API hoặc xử lý để lấy dữ liệu theo khoảng thời gian
        let filteredData = {};

        switch (period) {
            case 'today':
                filteredData = {
                    'Loại 1': 5,
                    'Loại 2': 10,
                    'Loại 3': 3
                };
                break;
            case 'monthly':
                filteredData = {
                    'Loại 1': 30,
                    'Loại 2': 50,
                    'Loại 3': 20
                };
                break;
            case 'yearly':
                filteredData = {
                    'Loại 1': 250,
                    'Loại 2': 300,
                    'Loại 3': 150
                };
                break;
        }

        // Cập nhật biểu đồ
        requestTypeChart.data.labels = Object.keys(filteredData);
        requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        requestTypeChart.update();

        // Cập nhật trạng thái nút
        updateButtonStyles(period);
    }

    // Hàm cập nhật màu sắc của các nút
    function updateButtonStyles(activePeriod) {
        // Xóa lớp 'active' từ tất cả các nút
        document.getElementById('btnToday').classList.remove('active');
        document.getElementById('btnMonthly').classList.remove('active');
        document.getElementById('btnYearly').classList.remove('active');

        // Thêm lớp 'active' vào nút tương ứng
        if (activePeriod === 'today') {
            document.getElementById('btnToday').classList.add('active');
        } else if (activePeriod === 'monthly') {
            document.getElementById('btnMonthly').classList.add('active');
        } else if (activePeriod === 'yearly') {
            document.getElementById('btnYearly').classList.add('active');
        }
    }
</script>
</body>
</html>
