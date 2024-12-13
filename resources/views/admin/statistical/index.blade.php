<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/statistical/index.css') }}">
    <title>Báo cáo</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <p>Báo cáo này tổng hợp số lượng yêu cầu hỗ trợ kỹ thuật của từng khách hàng đang hoạt động.</p>
                <div class="filter-container">
                    <select id="customerFilter" onchange="updateCustomerReport()">
                        <option value="all">Tất cả khách hàng</option>
                        @foreach ($activeCustomers as $customer)
                            <option value="{{ $customer->full_name }}">{{ $customer->full_name }}</option>
                        @endforeach
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

                        <select id="monthFilter" name="month">
                            <option value="all">Tất cả tháng</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>

                        <select id="yearFilter" name="year">
                            <option value="all">Tất cả năm</option>
                            @for ($year = 2020; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="row_start_end">
                        <div class="col-lg-6">
                            <div class="date-container">
                                <label for="startDate">Ngày bắt đầu</label>
                                <input type="date" id="startDate" name="startDate" value="{{ request('startDate') }}" placeholder="Ngày bắt đầu">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="date-container">
                                <label for="endDate">Ngày kết thúc</label>
                                <input type="date" id="endDate" name="endDate" value="{{ request('endDate') }}" placeholder="Ngày kết thúc">
                            </div>
                        </div>
                    </div>
                    <button type="submit">Thống kê</button>
                </form>
                <div class="chart-container">
                    <canvas id="requestTypeReport" style="margin-bottom: 15px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dữ liệu ban đầu cho báo cáo khách hàng
    const customerData = {
        @foreach($activeCustomers as $customer)
        '{{ $customer->full_name }}': {{ $customer->requests_count }},
        @endforeach
    };

    // Màu sắc cho từng khách hàng
    const customerColors = {
        @foreach($activeCustomers as $index => $customer)
        '{{ $customer->full_name }}': '{{ $customerColors[$index] }}', // hoặc màu bạn muốn
        @endforeach
    };

    // Báo cáo theo khách hàng
    const customerCtx = document.getElementById('customerReport').getContext('2d');
    let customerChart = new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(customerData), // Tên khách hàng
            datasets: [{
                label: 'Số yêu cầu',
                data: Object.values(customerData), // Số yêu cầu
                backgroundColor: Object.keys(customerData).map(name => customerColors[name]) // Màu sắc tương ứng
            }]
        }
    });

    // Cập nhật báo cáo theo khách hàng
    function updateCustomerReport() {
        const selectedCustomer = document.getElementById('customerFilter').value;
        let data = [];
        let labels = [];

        if (selectedCustomer === 'all') {
            data = Object.values(customerData); // Lấy tất cả số yêu cầu
            labels = Object.keys(customerData); // Nhãn cho tất cả khách hàng
        } else {
            // Lấy dữ liệu cho khách hàng đã chọn
            data = [customerData[selectedCustomer]]; // Lấy số yêu cầu của khách hàng đã chọn
            labels = [selectedCustomer]; // Nhãn cho khách hàng đã chọn
        }

        customerChart.data.datasets[0].data = data;

        // Cập nhật màu sắc cho biểu đồ
        customerChart.data.datasets[0].backgroundColor = selectedCustomer === 'all' ?
            Object.keys(customerData).map(name => customerColors[name]) :
            [customerColors[selectedCustomer]]; // Màu sắc của khách hàng đã chọn

        customerChart.data.labels = labels; // Cập nhật nhãn
        customerChart.update(); // Cập nhật biểu đồ
    }

    // Báo cáo theo loại yêu cầu
    document.addEventListener('DOMContentLoaded', function () {
        const requestTypes = @json($requestTypes);
        const labels = requestTypes.map(item => item.request_type_name);
        const data = requestTypes.map(item => item.count);

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

        window.updateRequestTypeReport = function () {
            const selectedType = document.getElementById('requestTypeFilter').value;

            if (selectedType === 'all') {
                requestTypeChart.data.labels = labels;
                requestTypeChart.data.datasets[0].data = data;
            } else {
                const filteredData = requestTypes.filter(item => item.request_type_name === selectedType);
                requestTypeChart.data.labels = filteredData.map(item => item.request_type_name);
                requestTypeChart.data.datasets[0].data = filteredData.map(item => item.count);
            }

            requestTypeChart.update();
        };
    });
</script>
</body>
</html>
