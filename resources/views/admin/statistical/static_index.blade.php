<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistical Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h1>Thống kê yêu cầu hỗ trợ</h1>

    <select id="chartSelector" onchange="showChart(this.value)">
        <option value="">Chọn biểu đồ</option>
        <option value="customer">Theo khách hàng</option>
        <option value="requestType">Theo loại yêu cầu</option>
        <option value="department">Theo phòng ban</option>
        <option value="time">Theo thời gian</option>
    </select>

    <div id="customerChart" class="chart" style="display:none;">
        <h2>Báo cáo theo khách hàng</h2>
        <div>
            <label for="customerNameFilter">Tên khách hàng:</label>
            <input type="text" id="customerNameFilter" oninput="filterCustomerChart()">
            <ul id="customerSuggestions" style="list-style: none; padding: 0;"></ul>
        </div>
        <div>
            <label for="customerStatusFilter">Trạng thái yêu cầu:</label>
            <select id="customerStatusFilter" onchange="filterCustomerChart()">
                <option value="">Tất cả trạng thái</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <canvas id="customerChartCanvas"></canvas>
    </div>

    <div id="requestTypeChart" class="chart" style="display:none;">
        <h2>Báo cáo theo loại yêu cầu</h2>
        <div>
            <label for="requestTypeDateFilter">Lọc theo thời gian:</label>
            <input type="date" id="requestTypeDateFilter" onchange="filterRequestTypeChart()">
        </div>
        <canvas id="requestTypeChartCanvas"></canvas>
    </div>

    <div id="departmentChart" class="chart" style="display:none;">
        <h2>Báo cáo theo phòng ban</h2>
        <div>
            <label for="departmentDateFilter">Lọc theo thời gian:</label>
            <input type="date" id="departmentDateFilter" onchange="filterDepartmentChart()">
        </div>
        <canvas id="departmentChartCanvas"></canvas>
    </div>

    <div id="timeChart" class="chart" style="display:none;">
        <h2>Báo cáo theo thời gian</h2>
        <div>
            <label for="timeDateFilter">Lọc theo thời gian:</label>
            <input type="date" id="timeDateFilter" onchange="filterTimeChart()">
        </div>
        <canvas id="timeChartCanvas"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Khi trang được tải, kiểm tra localStorage
    window.onload = function() {
        const selectedChart = localStorage.getItem('selectedChart');
        if (selectedChart) {
            document.getElementById('chartSelector').value = selectedChart; // Khôi phục giá trị select
            showChart(selectedChart); // Hiển thị biểu đồ tương ứng
        } else {
            showChart('customer'); // Hiển thị biểu đồ khách hàng mặc định
        }
    }

    function showChart(chartType) {
        // Ẩn tất cả biểu đồ
        const charts = document.querySelectorAll('.chart');
        charts.forEach(chart => chart.style.display = 'none');

        // Hiển thị biểu đồ tương ứng
        if (chartType) {
            document.getElementById(chartType + 'Chart').style.display = 'block';
            drawChart(chartType);
            // Lưu loại biểu đồ vào localStorage
            localStorage.setItem('selectedChart', chartType);
        }
    }

    function filterCustomerChart() {
        const customerName = document.getElementById('customerNameFilter').value;
        const customerStatus = document.getElementById('customerStatusFilter').value;

        // Gợi ý khách hàng
        fetch(`/api/search-customers?name=${customerName}`)
            .then(response => response.json())
            .then(data => {
                const suggestions = document.getElementById('customerSuggestions');
                suggestions.innerHTML = ''; // Xóa gợi ý cũ

                data.forEach(customer => {
                    const li = document.createElement('li');
                    li.textContent = customer.full_name; // Giả sử bạn có thuộc tính full_name
                    li.onclick = () => {
                        document.getElementById('customerNameFilter').value = customer.full_name; // Điền tên vào ô tìm kiếm
                        suggestions.innerHTML = ''; // Xóa gợi ý
                    };
                    suggestions.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching customer suggestions:', error));

        // Lấy dữ liệu thống kê khách hàng
        fetch(`/api/customer-stats?name=${customerName}&status=${customerStatus}`)
            .then(response => response.json())
            .then(data => {
                drawCustomerChart(data); // Cập nhật biểu đồ khách hàng
            })
            .catch(error => console.error('Error fetching customer stats:', error));
    }
    function drawCustomerChart(data) {
        const ctx = document.getElementById('customerChartCanvas').getContext('2d');
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

        const customerNames = [...new Set(data.map(item => item.full_name))];
        const statuses = ['Đang xử lý', 'Chưa xử lý', 'Hoàn thành', 'Đã hủy'];
        const requestCounts = {
            'Đang xử lý': [],
            'Chưa xử lý': [],
            'Hoàn thành': [],
            'Đã hủy': []
        };

        customerNames.forEach(name => {
            const customerData = data.filter(item => item.full_name === name);
            statuses.forEach(status => {
                const count = customerData.find(item => item.status === status)?.request_count || 0;
                requestCounts[status].push(count);
            });
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: customerNames,
                datasets: [
                    {
                        label: 'Đang xử lý',
                        data: requestCounts['Đang xử lý'],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Chưa xử lý',
                        data: requestCounts['Chưa xử lý'],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Hoàn thành',
                        data: requestCounts['Hoàn thành'],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Đã hủy',
                        data: requestCounts['Đã hủy'],
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    ////////////////////////////

    function filterRequestTypeChart() {
        const date = document.getElementById('requestTypeDateFilter').value;
        fetch(`/api/request-type-stats?date=${date}`)
            .then(response => response.json())
            .then(data => {
                drawRequestTypeChart(data);
            })
            .catch(error => console.error('Error fetching request type stats:', error));
    }

    function filterDepartmentChart() {
        const date = document.getElementById('departmentDateFilter').value;
        fetch(`/api/department-stats?date=${date}`)
            .then(response => response.json())
            .then(data => {
                drawDepartmentChart(data);
            })
            .catch(error => console.error('Error fetching department stats:', error));
    }

    function filterTimeChart() {
        const date = document.getElementById('timeDateFilter').value;
        fetch(`/api/time-stats?date=${date}`)
            .then(response => response.json())
            .then(data => {
                drawTimeChart(data);
            })
            .catch(error => console.error('Error fetching time stats:', error));
    }

    function drawChart(chartType) {
        let ctx, data;

        switch (chartType) {
            case 'customer':
                ctx = document.getElementById('customerChartCanvas').getContext('2d');
                // Gọi API để lấy dữ liệu cho từng khách hàng
                fetch('/api/customer-stats')
                    .then(response => response.json())
                    .then(stats => {
                        const labels = stats.map(item => item.full_name); // Tên khách hàng
                        const requestCounts = {
                            'Đang xử lý': [],
                            'Chưa xử lý': [],
                            'Hoàn thành': [],
                            'Đã hủy': []
                        };

                        // Nhóm số lượng yêu cầu theo trạng thái cho từng khách hàng
                        stats.forEach(item => {
                            requestCounts['Đang xử lý'].push(item['Đang xử lý'] || 0);
                            requestCounts['Chưa xử lý'].push(item['Chưa xử lý'] || 0);
                            requestCounts['Hoàn thành'].push(item['Hoàn thành'] || 0);
                            requestCounts['Đã hủy'].push(item['Đã hủy'] || 0);
                        });

                        // Xóa biểu đồ cũ trước khi vẽ biểu đồ mới
                        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Đang xử lý',
                                        data: requestCounts['Đang xử lý'],
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Chưa xử lý',
                                        data: requestCounts['Chưa xử lý'],
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Hoàn thành',
                                        data: requestCounts['Hoàn thành'],
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Đã hủy',
                                        data: requestCounts['Đã hủy'],
                                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                        borderColor: 'rgba(255, 206, 86, 1)',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching customer stats:', error));
                break;

            case 'requestType':
                ctx = document.getElementById('requestTypeChartCanvas').getContext('2d');
                data = @json($requestTypeStats);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.request_type_name),
                        datasets: [{
                            label: 'Số lượng yêu cầu',
                            data: data.map(item => item.request_count),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                            ],
                            borderWidth: 1
                        }]
                    }
                });
                break;

            case 'department':
                ctx = document.getElementById('departmentChartCanvas').getContext('2d');
                data = @json($departmentStats);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.department_name),
                        datasets: [{
                            label: 'Số lượng yêu cầu',
                            data: data.map(item => item.request_count),
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                break;

            case 'time':
                ctx = document.getElementById('timeChartCanvas').getContext('2d');
                data = @json($timeStats);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.request_date),
                        datasets: [{
                            label: 'Số lượng yêu cầu',
                            data: data.map(item => item.request_count),
                            fill: false,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            tension: 0.1
                        }]
                    }
                });
                break;
        }
    }

</script>

</body>
</html>
