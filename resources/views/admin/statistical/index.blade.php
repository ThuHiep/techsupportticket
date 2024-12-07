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
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .report-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .report-section h3 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #333;
        }
        .chart-container {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        button, input[type="submit"] {
            background-color: #3498db; /* Màu nền */
            color: white; /* Màu chữ */
            border: none; /* Loại bỏ viền */
            padding: 8px 15px; /* Kích thước nút */
            border-radius: 5px; /* Bo góc */
            font-size: 14px; /* Kích thước chữ */
            cursor: pointer; /* Thay đổi con trỏ */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #1abc9c; /* Màu khi hover */
            transform: scale(1.05); /* Hiệu ứng phóng to nhẹ */
        }

        button:active, input[type="submit"]:active {
            background-color: #2980b9; /* Màu khi bấm */
            transform: scale(0.95); /* Hiệu ứng thu nhỏ */
        }

        select, input[type="date"] {
            padding: 8px; /* Kích thước */
            font-size: 14px; /* Kích thước chữ */
            border: 1px solid #ccc; /* Viền */
            border-radius: 5px; /* Bo góc */
            outline: none; /* Loại bỏ đường viền khi focus */
            transition: border-color 0.3s ease;
        }

        select:focus, input[type="date"]:focus {
            border-color: #3498db; /* Đổi màu viền khi focus */
        }

    </style>
</head>
<body>
<div class="container">
    <div>
        <h1>Báo cáo</h1>
    </div>
    <div class="row">
        <!-- Báo cáo theo khách hàng -->
        <div class="col-lg-6">
            <div class="report-section">
                <h3>Báo cáo theo khách hàng</h3>
                <p>Báo cáo này tổng hợp số lượng yêu cầu hỗ trợ kỹ thuật của từng khách hàng.</p>
                <div class="filter-container">
                    <select id="customerFilter" onchange="updateCustomerReport()">
                        <option value="all">Tất cả khách hàng</option>
                        <option value="A">Khách hàng A</option>
                        <option value="B">Khách hàng B</option>
                        <option value="C">Khách hàng C</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="customerReport"></canvas>
                </div>
            </div>
        </div>

        <!-- Báo cáo theo loại yêu cầu -->
        <div class="col-lg-6">
            <div class="report-section">
                <h3>Báo cáo theo loại yêu cầu</h3>
                <p>Báo cáo này cung cấp thông tin về số lượng yêu cầu hỗ trợ theo từng loại yêu cầu.</p>
                <form method="GET" action="{{ route('statistical.index') }}">
                    <div class="filter-container">
                        <select id="requestTypeFilter" name="requestTypeFilter" onchange="updateRequestTypeReport()">
                        <option value="all">Tất cả loại yêu cầu</option>
                            @foreach ($requestTypes as $type)
                                <option value="{{ $type->request_type_name }}">{{ $type->request_type_name }}</option>
                            @endforeach
                        </select>

                        <!-- Dropdown chọn tháng -->
                        <select id="monthFilter" name="month">
                            <option value="all">Tất cả tháng</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>

                        <!-- Dropdown chọn năm -->
                        <select id="yearFilter" name="year">
                            <option value="all">Tất cả năm</option>
                            @for ($year = 2020; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <!-- Bộ chọn ngày -->
                    <input type="date" name="startDate" value="{{ request('startDate') }}" placeholder="Ngày bắt đầu">
                    <input type="date" name="endDate" value="{{ request('endDate') }}" placeholder="Ngày kết thúc">
                    <button type="submit">Thống kê</button>
                </form>
                <div class="chart-container">
                    <canvas id="requestTypeReport"></canvas>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Báo cáo theo khách hàng
    const customerCtx = document.getElementById('customerReport').getContext('2d');
    let customerChart = new Chart(customerCtx, {
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

    // Cập nhật báo cáo theo khách hàng
    function updateCustomerReport() {
        const selectedCustomer = document.getElementById('customerFilter').value;
        let data = [12, 19, 7]; // Dữ liệu mặc định

        if (selectedCustomer === 'A') {
            data = [12];
        } else if (selectedCustomer === 'B') {
            data = [19];
        } else if (selectedCustomer === 'C') {
            data = [7];
        }

        customerChart.data.datasets[0].data = data;
        customerChart.update();
    }

    // Báo cáo theo loại yêu cầu
    document.addEventListener('DOMContentLoaded', function () {
        // Lấy dữ liệu từ server
        const requestTypes = @json($requestTypes);

        // Chuẩn bị dữ liệu cho biểu đồ
        const labels = requestTypes.map(item => item.request_type_name);
        const data = requestTypes.map(item => item.count);

        // Khởi tạo biểu đồ
        const ctx = document.getElementById('requestTypeReport').getContext('2d');
        const requestTypeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#3498db', '#1abc9c', '#e74c3c', '#f1c40f', '#9b59b6']
                }]
            }
        });

        // Hàm cập nhật biểu đồ khi thay đổi bộ lọc
        window.updateRequestTypeReport = function () {
            const selectedType = document.getElementById('requestTypeFilter').value;

            if (selectedType === 'all') {
                // Hiển thị toàn bộ dữ liệu
                requestTypeChart.data.labels = labels;
                requestTypeChart.data.datasets[0].data = data;
            } else {
                // Lọc dữ liệu theo loại yêu cầu
                const filteredData = requestTypes.filter(item => item.request_type_name === selectedType);
                requestTypeChart.data.labels = filteredData.map(item => item.request_type_name);
                requestTypeChart.data.datasets[0].data = filteredData.map(item => item.count);
            }

            // Cập nhật biểu đồ
            requestTypeChart.update();
        };
    });

</script>
</body>
</html>
