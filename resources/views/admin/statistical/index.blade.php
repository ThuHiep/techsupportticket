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
        .chart-container {
            width: 300px; /* Set desired width */
            height: 300px; /* Set desired height */
            margin: auto; /* Center the chart */
        }
        body .container {
        width: calc(98%); /* Độ rộng sau khi trừ sidebar */
        transition: all 0.3s ease-in-out;
    }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%); /* Mở rộng nội dung khi sidebar thu nhỏ */
            transition: all 0.3s ease-in-out;
        }
        .required {
            color: red;
            font-size: 14px;
        }
    </style>

</head>
<body>
    <div class="container">
        <div>
            <h1>Báo cáo thống kê</h1>
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
                    <div class="chart-containers">
                        <canvas id="customerReport"></canvas>
                    </div>
                </div>
            </div>

            <!-- Báo cáo theo loại yêu cầu -->
            <div class="col-lg-6">
                <div class="report-section">
                    <h3>Báo cáo theo loại yêu cầu</h3>
                    <p>Báo cáo này cung cấp thông tin về số lượng yêu cầu hỗ trợ theo từng loại yêu cầu.</p>
                    <!-- In your form for filtering -->
                    <form method="GET" action="{{ route('statistical.index') }}">
                        <div class="filter-container">
                            <select id="requestTypeFilter" name="requestTypeFilter" onchange="updateRequestTypeChart()">
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
                                    <input type="date" id="startDate" name="startDate" value="{{ request('startDate') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="date-container">
                                    <label for="endDate">Ngày kết thúc</label>
                                    <input type="date" id="endDate" name="endDate" value="{{ request('endDate') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit">Thống kê</button>
                    </form>
                    <div class="chart-container">
                        <canvas id="requestTypeChart"></canvas>
                    </div>
    {{--                <div class="chart-container">--}}
    {{--                    <canvas id="monthlyReport"></canvas>--}}
    {{--                </div>--}}
    {{--                <div class="chart-container">--}}
    {{--                    <canvas id="yearlyReport"></canvas>--}}
    {{--                </div>--}}
                {{-- </div>
            </div>
        </div> --}}
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
        '{{ $customer->full_name }}': '{{ $customerColors[$index] }}',
        @endforeach
    };

    // Báo cáo theo khách hàng
    const customerCtx = document.getElementById('customerReport').getContext('2d');
    let customerChart = new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(customerData),
            datasets: [{
                label: 'Số yêu cầu',
                data: Object.values(customerData),
                backgroundColor: Object.keys(customerData).map(name => customerColors[name])
            }]
        }
    });

    // Cập nhật báo cáo theo khách hàng
    function updateCustomerReport() {
        const selectedCustomer = document.getElementById('customerFilter').value;
        let data = [];
        let labels = [];

        if (selectedCustomer === 'all') {
            data = Object.values(customerData);
            labels = Object.keys(customerData);
        } else {
            data = [customerData[selectedCustomer]];
            labels = [selectedCustomer];
        }

        customerChart.data.datasets[0].data = data;
        customerChart.data.labels = labels;
        customerChart.data.datasets[0].backgroundColor = selectedCustomer === 'all' ?
            Object.keys(customerData).map(name => customerColors[name]) :
            [customerColors[selectedCustomer]];

        customerChart.update();
    }

    // Biểu đồ loại yêu cầu (Pie Chart)
    const requestTypeCtx = document.getElementById('requestTypeChart').getContext('2d');
    let requestTypeChart = new Chart(requestTypeCtx, {
        type: 'pie',
        data: {
            labels: [], // Tên loại yêu cầu
            datasets: [{
                label: 'Số yêu cầu theo loại',
                data: [], // Số yêu cầu
                backgroundColor: [], // Màu sắc
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Allow manual control of aspect ratio
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw}`; // Display label with count
                        }
                    }
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const initialData = {
            @foreach($requestTypes as $type)
            '{{ $type->request_type_name }}': {{ $type->count }},
            @endforeach
        };

        const initialLabels = Object.keys(initialData);
        const initialValues = Object.values(initialData);

        requestTypeChart.data.labels = initialLabels;
        requestTypeChart.data.datasets[0].data = initialValues;
        requestTypeChart.data.datasets[0].backgroundColor = initialLabels.map(() => getRandomColor()); // Assign colors
        requestTypeChart.update();
    });
    // Hàm này sẽ được gọi khi trang được tải
    document.addEventListener('DOMContentLoaded', function() {
        fetchRequests();
    });

    function fetchRequests() {
        fetch('/api/requests')
            .then(response => response.json())
            .then(data => {
                // Dữ liệu yêu cầu đã được lấy thành công
                console.log(data);
                // Bạn có thể sử dụng dữ liệu này để cập nhật giao diện, ví dụ như biểu đồ hoặc bảng
            })
            .catch(error => console.error('Error fetching requests:', error));
    }

    function updateRequestTypeChart() {
        const selectedType = document.getElementById('requestTypeFilter').value;

        // Fetch all requests again to filter
        fetch('/api/requests')
            .then(response => response.json())
            .then(allRequests => {
                const filteredRequests = selectedType === 'all' ? allRequests : allRequests.filter(request => request.request_type_name === selectedType);

                // Cập nhật biểu đồ
                const data = prepareChartData(filteredRequests);
                requestTypeChart.data.labels = data.labels;
                requestTypeChart.data.datasets[0].data = data.values;
                requestTypeChart.data.datasets[0].backgroundColor = data.colors;
                requestTypeChart.update();
            })
            .catch(error => console.error('Error fetching requests:', error));
    }

    function prepareChartData(requests) {
        const counts = {};
        requests.forEach(request => {
            if (!counts[request.request_type_name]) {
                counts[request.request_type_name] = 0;
            }
            counts[request.request_type_name]++;
        });

        return {
            labels: Object.keys(counts),
            values: Object.values(counts),
            colors: Object.keys(counts).map(() => getRandomColor()), // Assuming you have a function to generate random colors
        };
    }

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Biểu đồ theo tháng (Line Chart)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the monthly chart
        const monthlyCtx = document.getElementById('monthlyReport').getContext('2d');
        let monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [], // Will be filled with dates
                datasets: [{
                    label: 'Số yêu cầu theo tháng',
                    data: [], // Will be filled with counts
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        },
                        title: {
                            display: true,
                            text: 'Ngày'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số yêu cầu'
                        }
                    }
                }
            }
        });

        // Trigger update on page load if filters are set
        updateMonthlyChart();

        // Function to update the monthly chart
        function updateMonthlyChart() {
            const selectedMonth = document.getElementById('monthFilter').value;
            const selectedYear = document.getElementById('yearFilter').value;

            fetch(`/api/requests/monthly?month=${selectedMonth}&year=${selectedYear}`)
                .then(response => response.json())
                .then(responseData => {
                    const data = responseData.data;
                    const labels = Object.keys(data);
                    const values = Object.values(data);

                    // Clear previous data
                    monthlyChart.data.labels = labels;
                    monthlyChart.data.datasets[0].data = values;

                    // Update the chart
                    monthlyChart.update();
                })
                .catch(error => console.error('Error fetching monthly data:', error));
        }

        // Attach the update function to the form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            updateMonthlyChart(); // Update the chart based on selected filters
        });
    });

    // Biểu đồ theo năm (Line Chart)
    const yearlyCtx = document.getElementById('yearlyReport').getContext('2d');
    let yearlyChart = new Chart(yearlyCtx, {
        type: 'line',
        data: {
            labels: [], // Dates
            datasets: []
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'month'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateYearlyChart() {
        const selectedYear = document.getElementById('yearFilter').value;

        fetch(`/api/requests/yearly?year=${selectedYear}`)
            .then(response => response.json())
            .then(responseData => {
                const data = responseData.data;
                const labels = Object.keys(data);
                const datasets = [];

                for (const [month, types] of Object.entries(data)) {
                    for (const [type, count] of Object.entries(types)) {
                        if (!datasets.find(dataset => dataset.label === type)) {
                            datasets.push({
                                label: type,
                                data: [],
                                borderColor: getRandomColor(),
                                fill: false
                            });
                        }
                        const dataset = datasets.find(dataset => dataset.label === type);
                        dataset.data.push({ x: month, y: count });
                    }
                }

                yearlyChart.data.labels = labels;
                yearlyChart.data.datasets = datasets;
                yearlyChart.update();
            });
    }

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>
</body>
</html>
