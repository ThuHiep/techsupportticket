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
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        .chart-container {
            width: 100%; /* Đặt chiều rộng bằng 100% của container */
            height: 500px; /* Tăng chiều cao của biểu đồ */
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
                <form method="GET" action="{{ route('statistical.index') }}">
                    <div class="filter-container">
                        <button id="btnToday" type="button" onclick="filterBy('today')">Ngày</button>
                        <button id="btnMonthly" type="button" onclick="filterBy('monthly')">Tháng</button>
                        <button id="btnYearly" type="button" onclick="filterBy('yearly')">Năm</button>
                        <!-- Search theo ngày -->
                        <label for="startDate" class="filter-label">Từ:</label>
                        <input type="date" id="startDate" onchange="filterByDates()">
                        <label for="endDate" class="filter-label">Đến:</label>
                        <input type="date" id="endDate" onchange="filterByDates()">
                    </div>
                        <canvas id="requestTypeChart"></canvas>
                </form>
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
    const initialData = {
        @foreach($requestTypes as $type)
        '{{ $type->request_type_name }}': {{ $type->requests_count }},
        @endforeach
    };

    const ctx = document.getElementById('requestTypeChart').getContext('2d');
    let requestTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(initialData),
            datasets: [{
                label: 'Số yêu cầu ',
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
    async function filterByDates() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Kiểm tra xem cả hai ngày có được nhập hay không
        if (startDate && endDate) {
            let url = `http://localhost:8000/api/get-request-data?startDate=${startDate}&endDate=${endDate}`;

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    const errorMessage = await response.text();
                    throw new Error('Network response was not ok: ' + errorMessage);
                }
                const filteredData = await response.json();
                updateChartWithFilteredData(filteredData);
            } catch (error) {
                console.error('Error fetching data:', error);
                alert('Đã xảy ra lỗi khi tải dữ liệu: ' + error.message);
            }
        }
    }

    async function filterBy(period) {
        let filteredData = {};
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        let url = `http://localhost:8000/api/get-request-data?period=${period}`;

        // Nếu người dùng nhập khoảng thời gian, thêm startDate và endDate vào URL
        if (startDate && endDate) {
            await filterByDates();
            return; // Dừng lại không thực hiện tiếp
        }

        try {
            const response = await fetch(url);
            if (!response.ok) {
                const errorMessage = await response.text();
                throw new Error('Network response was not ok: ' + errorMessage);
            }
            filteredData = await response.json();
            updateChartWithFilteredData(filteredData);
        } catch (error) {
            console.error('Error fetching data:', error);
            alert('Đã xảy ra lỗi khi tải dữ liệu: ' + error.message);
            return;
        }

        // Cập nhật biểu đồ theo dữ liệu lọc
        if (period === 'monthly') {
            // Đảm bảo có đủ 30 ngày cho tháng
            const allDaysInMonth = Array.from({ length: 30 }, (_, i) => i + 1);
            const data = allDaysInMonth.map(day => filteredData[day] || 0); // Mặc định là 0 nếu không có dữ liệu
            requestTypeChart.data.labels = allDaysInMonth.map(day => `Ngày ${day}`);
            requestTypeChart.data.datasets[0].data = data;
        } else {
            requestTypeChart.data.labels = Object.keys(filteredData);
            requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        }

        // Cập nhật lại biểu đồ
        requestTypeChart.update();
        updateButtonStyles(period);
        displaySpecificData(filteredData);
    }

    function updateChartWithFilteredData(filteredData) {
        requestTypeChart.data.labels = Object.keys(filteredData);
        requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        requestTypeChart.update();
    }

    function updateButtonStyles(activePeriod) {
        console.log('Active Period:', activePeriod); // Debugging line
        document.getElementById('btnToday').classList.remove('active');
        document.getElementById('btnMonthly').classList.remove('active');
        document.getElementById('btnYearly').classList.remove('active');

        if (activePeriod === 'today') {
            document.getElementById('btnToday').classList.add('active');
        } else if (activePeriod === 'monthly') {
            document.getElementById('btnMonthly').classList.add('active');
        } else if (activePeriod === 'yearly') {
            document.getElementById('btnYearly').classList.add('active');
        }
    }

    function displaySpecificData(filteredData) {
        const dataList = document.getElementById('dataList');
        dataList.innerHTML = ''; // Clear previous data

        for (const [key, value] of Object.entries(filteredData)) {
            const listItem = document.createElement('li');
            listItem.textContent = `${key}: ${value}`; // Modify as per your data structure
            dataList.appendChild(listItem);
        }
    }


</script>

</body>
</html>
