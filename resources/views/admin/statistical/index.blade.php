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
            width: 100%; /* Full width for responsiveness */
            height: 300px; /* Set desired height */
            margin: auto; /* Center the chart */
        }
        body .container {
            width: calc(98%); /* Width after sidebar */
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
    <h1>Báo cáo thống kê</h1>
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
                <form id="filterForm" method="GET" action="{{ route('statistical.index') }}">
                    <div class="filter-container">
                        <select id="requestTypeFilter" name="requestTypeFilter" onchange="updateReports()">
                            <option value="all">Tất cả loại yêu cầu</option>
                            @foreach ($requestTypes as $type)
                                <option value="{{ $type->request_type_name }}">{{ $type->request_type_name }}</option>
                            @endforeach
                        </select>

                        <select id="monthFilter" name="month" onchange="updateReports()">
                            <option value="all">Tất cả tháng</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                            @endfor
                        </select>

                        <select id="yearFilter" name="year" onchange="updateReports()">
                            <option value="all">Tất cả năm</option>
                            @for ($year = 2020; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        <!-- Nút Thống Kê -->
                        <button id="statisticButton" onclick="updateReports()">Thống kê</button>
                    </div>
                </form>
                <div class="chart-container" id="requestTypeChartContainer">
                    <canvas id="requestTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ theo tháng -->
{{--    <div class="chart-container" id="monthlyChartContainer" style="display: none;">--}}
{{--        <canvas id="monthlyReport"></canvas>--}}
{{--    </div>--}}
</div>

<script>
    // Initializing Customer Chart
    const customerData = {
        @foreach($activeCustomers as $customer)
        '{{ $customer->full_name }}': {{ $customer->requests_count }},
        @endforeach
    };

    const customerColors = {
        @foreach($activeCustomers as $index => $customer)
        '{{ $customer->full_name }}': '{{ $customerColors[$index] }}',
        @endforeach
    };

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

    // Request Type Chart - Line Chart
    const requestTypeCtx = document.getElementById('requestTypeChart').getContext('2d');
    let requestTypeChart = new Chart(requestTypeCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($requestTypes as $type)
                    '{{ $type->request_type_name }}',
                @endforeach
            ],
            datasets: [{
                label: 'Số yêu cầu theo loại',
                data: [
                    @foreach($requestTypes as $type)
                        {{ $type->count }},
                    @endforeach
                ],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                tension: 0.4 // To make the line curve
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Loại yêu cầu'
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

    function updateReports() {
        const selectedType = document.getElementById('requestTypeFilter').value;
        const selectedMonth = document.getElementById('monthFilter').value;
        const selectedYear = document.getElementById('yearFilter').value;

        // Gọi API để lấy dữ liệu theo các bộ lọc đã chọn
        fetch(`/api/requests?type=${selectedType}&month=${selectedMonth}&year=${selectedYear}`)
            .then(response => response.json())
            .then(responseData => {
                const labels = responseData.length ? responseData.map(item => item.request_type_name) : ['Không có dữ liệu'];
                const data = responseData.length ? responseData.map(item => item.count) : [0];

                requestTypeChart.data.labels = labels;
                requestTypeChart.data.datasets[0].data = data;
                requestTypeChart.update(); // Cập nhật biểu đồ
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Event listener for form submission
    document.querySelector('#filterForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        updateReports(); // Update the chart based on selected filters
    });

    // Gọi hàm cập nhật khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        updateCustomerReport(); // Cập nhật báo cáo khách hàng
        updateReports(); // Cập nhật báo cáo loại yêu cầu
    });

    // Request Type Chart - Line Chart
    // const requestTypeCtx = document.getElementById('requestTypeChart').getContext('2d');
    // let requestTypeChart = new Chart(requestTypeCtx, {
    //     type: 'line',
    //     data: {
    //         labels: [], // Sẽ được cập nhật trong updateReports
    //         datasets: [] // Sẽ được cập nhật trong updateReports
    //     },
    //     options: {
    //         responsive: true,
    //         scales: {
    //             x: {
    //                 title: {
    //                     display: true,
    //                     text: 'Ngày'
    //                 }
    //             },
    //             y: {
    //                 beginAtZero: true,
    //                 title: {
    //                     display: true,
    //                     text: 'Số yêu cầu'
    //                 }
    //             }
    //         },
    //         plugins: {
    //             tooltip: {
    //                 mode: 'index',
    //                 intersect: false
    //             }
    //         },
    //         elements: {
    //             line: {
    //                 tension: 0.4 // Để làm cho đường cong
    //             },
    //             point: {
    //                 radius: 5 // Kích thước điểm
    //             }
    //         }
    //     }
    // });
    //
    // function updateReports() {
    //     const selectedType = document.getElementById('requestTypeFilter').value;
    //     const selectedMonth = document.getElementById('monthFilter').value;
    //     const selectedYear = document.getElementById('yearFilter').value;
    //
    //     fetch(`/api/requests?type=${selectedType}&month=${selectedMonth}&year=${selectedYear}`)
    //         .then(response => {
    //             if (!response.ok) {
    //                 throw new Error('Network response was not ok');
    //             }
    //             return response.json();
    //         })
    //         .then(data => {
    //             const labels = Array.from({ length: 31 }, (_, i) => (i + 1).toString()); // Ngày từ 1 đến 31
    //             const datasets = [];
    //
    //             // Giả sử data.request_types có cấu trúc như sau:
    //             // data.request_types = [{ request_type_name: 'Type 1', counts: [0, 1, 2, ...] }, ...]
    //             data.request_types.forEach(item => {
    //                 datasets.push({
    //                     label: item.request_type_name,
    //                     data: item.counts, // Chắc chắn counts là mảng chứa dữ liệu cho từng ngày
    //                     borderColor: getRandomColor(),
    //                     backgroundColor: 'rgba(0, 255, 255, 0.2)',
    //                     fill: true,
    //                     tension: 0.4
    //                 });
    //             });
    //
    //             requestTypeChart.data.labels = labels;
    //             requestTypeChart.data.datasets = datasets;
    //             requestTypeChart.update();
    //         })
    //         .catch(error => console.error('Error fetching data:', error));
    // }
</script>
</body>
</html>
