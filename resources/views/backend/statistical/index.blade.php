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
        select, input {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
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
                        <select id="requestTypeFilter" onchange="updateRequestTypeReport()">
                            <option value="all">Tất cả loại yêu cầu</option>
                            @foreach ($requestTypes as $type)
                                <option value="{{ $type->request_type_name }}">{{ $type->request_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Bộ chọn ngày -->
                    <input type="date" name="startDate" value="{{ request('startDate') }}" placeholder="Ngày bắt đầu">
                    <input type="date" name="endDate" value="{{ request('endDate') }}" placeholder="Ngày kết thúc">
                    <button type="submit">Lọc theo ngày</button>
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
        const ctx = document.getElementById('requestTypeReport').getContext('2d');
        const requestTypes = @json($requestTypes);

        if (requestTypes.length === 0) {
            console.error('No data available');
            return;
        }

        const labels = requestTypes.map(item => item.request_type_name);
        const values = requestTypes.map(item => item.count);

        let requestTypeChart = new Chart(ctx, {
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

        // Cập nhật báo cáo theo loại yêu cầu
        window.updateRequestTypeReport = function() {
            const selectedType = document.getElementById('requestTypeFilter').value;
            let filteredLabels = labels;
            let filteredValues = values;

            if (selectedType !== 'all') {
                const filteredData = requestTypes.filter(item => item.request_type_name === selectedType);
                filteredLabels = filteredData.map(item => item.request_type_name);
                filteredValues = filteredData.map(item => item.count);
            }

            requestTypeChart.data.labels = filteredLabels;
            requestTypeChart.data.datasets[0].data = filteredValues;
            requestTypeChart.update();
        }
    });
</script>
</body>
</html>
