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
        .container {
            width: calc(98%);
            margin: auto;
            transition: all 0.3s ease-in-out;
        }
        .chart-container {
            width: 100%;
            height: 500px;
            font-size: 14px;
        }
        .report-select-container {
            text-align: center;
            margin: 20px 0;
        }
        h1 {
            color: orange;
            text-align: center;
        }
        .report-section {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            margin: 10px 0;
            overflow: hidden;
            position: relative;
        }
        .progress {
            height: 100%;
            background: #4caf50; /* Màu xanh */
            transition: width 0.4s ease;
        }
        .filter-container {
            display: flex;
            justify-content: center; /* Căn giữa các phần tử */
            align-items: center; /* Căn giữa theo chiều dọc */
            gap: 15px; /* Khoảng cách giữa các phần tử */
            margin-bottom: 20px; /* Khoảng cách dưới cùng */
        }

        .filter-group {
            display: flex;
            flex-direction: column; /* Đặt label và select theo chiều dọc */
            align-items: center; /* Căn giữa các phần tử trong nhóm */
        }

        .custom-select {
            padding: 8px;
            border: 1px solid #ced4da; /* Màu viền */
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-select:focus {
            border-color: #007bff; /* Màu viền khi focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Hiệu ứng bóng khi focus */
            outline: none;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: #ff7f50; /* Màu nền nút */
            color: white; /* Màu chữ */
        }

        .btn-primary:hover {
            background-color: #e67e22; /* Màu nền khi hover */
            transform: scale(1.05); /* Phóng to một chút khi hover */
        }

    </style>
</head>
<body>
<div class="container">
    <div class="report-select-container">
        <h1>Báo cáo thống kê</h1>
        <label for="reportSelect" class="filter-label"></label>
        <select id="reportSelect" onchange="showSelectedChart()">
            <option value="customer">Báo cáo theo khách hàng</option>
            <option value="requestType">Báo cáo theo loại yêu cầu</option>
            <option value="department">Báo cáo theo phòng ban</option>
            <option value="time">Báo cáo theo thời gian</option>
        </select>
    </div>
    <div class="row">
        <!-- Cột trái - Biểu đồ -->
        <div class="col-lg-8" id="chartContainer">
            <!--Biểu đồ khách hàng-->
            <div class="report-section" id="customerReportContainer" style="display: block;">
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
            <!--Biểu đồ loại yêu cầu-->
            <div class="report-section" id="requestTypeReportContainer" style="display: none;">
                <h3>Báo cáo theo loại yêu cầu</h3>
                <div class="filter-container">
                    <button id="btnToday" type="button" onclick="filterBy('today')">Ngày</button>
                    <button id="btnMonthly" type="button" onclick="filterBy('monthly')">Tháng</button>
                    <button id="btnYearly" type="button" onclick="filterBy('yearly')">Năm</button>
                    <label for="startDate" class="filter-label">Từ:</label>
                    <input type="date" id="startDate" onchange="filterByDates()">
                    <label for="endDate" class="filter-label">Đến:</label>
                    <input type="date" id="endDate" onchange="filterByDates()">
                </div>
                <canvas id="requestTypeChart"></canvas>
            </div>
            <!--Biểu đồ phòng ban-->
            <div class="report-section" id="departmentReportContainer">
                <h3>Báo cáo theo phòng ban</h3>
                <p>Báo cáo này giúp quản lý số lượng yêu cầu hỗ trợ được xử lý bởi từng phòng ban.</p>
                <div class="filter-container">
                    <label for="departmentFilter" class="filter-label"></label>
                    <select id="departmentFilter" onchange="updateDepartmentReport()">
                        <option value="all">Tất cả phòng ban</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
            <!--Biểu đồ báo cáo yêu cầu theo thời gian-->
            <div class="report-section" id="timeReportContainer" style="display: none;">
                <h3>Báo cáo số yêu cầu theo thời gian</h3>
                <div class="filter-container">
                    <select id="departmentSelect">
                        <option value="">Phòng ban</option>
                    </select>

                    <select id="statusSelect">
                        <option value="">Trạng thái</option>
                        <option value="pending">Chưa xử lý</option>
                        <option value="in_progress">Đang xử lý</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="canceled">Đã Hủy</option>
                    </select>

                    <select id="typeSelect">
                        <option value="">Loại yêu cầu</option>
                    </select>

                    <select id="timeLevelSelect" onchange="handleLevelChange()">
                        <option value="year">Năm</option>
                        <option value="month">Tháng</option>
                        <option value="day">Ngày</option>
                    </select>
                    <button id="btnFilter" type="button" onclick="applyFilters()">Lọc</button>
                </div>
                <canvas id="timeReport"></canvas>
            </div>
        </div>

        <!-- Cột phải - Số liệu cụ thể -->
        <div class="col-lg-4" id="dataContainer">
            <div class="report-section" id="customerDataContainer" style="display: block;">
                <h3>Số liệu tổng hợp</h3>
                <p id="totalCustomerRequests"></p>
                <div class="progress-bar">
                    <div class="progress" style="width: 58%;"></div>
                </div>
                <ul id="customerDataList"></ul>
            </div>
            <div class="report-section" id="requestTypeDataContainer" style="display: none;">
                <h3>Số liệu tổng hợp</h3>
                <p id="totalRequestTypeRequests"></p>
                <div class="progress-bar">
                    <div class="progress" style="width: 22%;"></div>
                </div>
                <ul id="requestTypeDataList"></ul>
            </div>
            <!--Số liệu phòng ban-->
            <div class="report-section" id="departmentDataContainer" style="display: none;">
                <h3>Số liệu tổng hợp theo phòng ban</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Phòng ban</th>
                        <th>Đang xử lý</th>
                        <th>Chưa xử lý</th>
                        <th>Hoàn thành</th>
                        <th>Đã hủy</th>
                    </tr>
                    </thead>
                    <tbody id="departmentDataList"></tbody>
                </table>
                <p id="totalDepartmentRequests"></p>
            </div>
            <!--Số liệu thời gian-->
            <div class="report-section" id="timeDataContainer" style="display: none;">
                <h3>Số liệu tổng hợp</h3>
                <p id="totalTimeRequests"></p>
                <ul id="timeDataList"></ul>
            </div>
        </div>
    </div>
</div>

<script>
    function showSelectedChart() {
        const selectedReport = document.getElementById('reportSelect').value;
        localStorage.setItem('selectedReport', selectedReport);

        // Lấy các container báo cáo
        const customerReportContainer = document.getElementById('customerReportContainer');
        const requestTypeReportContainer = document.getElementById('requestTypeReportContainer');
        const departmentReportContainer = document.getElementById('departmentReportContainer');
        const timeReportContainer = document.getElementById('timeReportContainer');

        // Lấy các container dữ liệu
        const customerDataContainer = document.getElementById('customerDataContainer');
        const requestTypeDataContainer = document.getElementById('requestTypeDataContainer');
        const departmentDataContainer = document.getElementById('departmentDataContainer');
        const timeDataContainer = document.getElementById('timeDataContainer'); // Thêm khai báo cho timeDataContainer

        // Hiển thị hoặc ẩn các phần tử dựa trên báo cáo đã chọn
        customerReportContainer.style.display = selectedReport === 'customer' ? 'block' : 'none';
        requestTypeReportContainer.style.display = selectedReport === 'requestType' ? 'block' : 'none';
        departmentReportContainer.style.display = selectedReport === 'department' ? 'block' : 'none';
        timeReportContainer.style.display = selectedReport === 'time' ? 'block' : 'none';

        customerDataContainer.style.display = selectedReport === 'customer' ? 'block' : 'none';
        requestTypeDataContainer.style.display = selectedReport === 'requestType' ? 'block' : 'none';
        departmentDataContainer.style.display = selectedReport === 'department' ? 'block' : 'none';
        timeDataContainer.style.display = selectedReport === 'time' ? 'block' : 'none'; // Hiển thị dữ liệu của time nếu được chọn

        // Cập nhật dữ liệu báo cáo tương ứng
        if (selectedReport === 'customer') {
            updateCustomerReport();
        } else if (selectedReport === 'requestType') {
            updateRequestTypeData();
        } else if (selectedReport === 'department') {
            updateDepartmentReport();
        } else if (selectedReport === 'time') {
            updateTimeReport('today'); // Cập nhật dữ liệu thời gian, mặc định là hôm nay
        }
    }


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
    // Dữ liệu của phòng ban
    const departmentData = {
        @foreach ($departments as $department)
        '{{ $department->department_name  }}': {{ $department->requests_count }},
        @endforeach
    };

    // Màu của phòng ban
    const departmentColors = {
        @foreach ($departments as $department)
        '{{ $department->department_name }}': '{{ $departmentColors[$department->department_name] }}',
        @endforeach
    };

    // Biểu đồ khách hàng
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
        displayCustomerData(selectedCustomer);
    }

    function displayCustomerData(selectedCustomer) {
        const customerDataList = document.getElementById('customerDataList');
        const totalCustomerRequests = document.getElementById('totalCustomerRequests');
        customerDataList.innerHTML = '';

        if (selectedCustomer === 'all') {
            let totalRequests = 0;
            for (const [key, value] of Object.entries(customerData)) {
                const listItem = document.createElement('li');
                listItem.textContent = `${key}: ${value} yêu cầu`;
                customerDataList.appendChild(listItem);
                totalRequests += value; // Tính tổng số yêu cầu
            }
            totalCustomerRequests.textContent = `Tổng số yêu cầu: ${totalRequests}`; // Hiển thị tổng số yêu cầu
        } else {
            const listItem = document.createElement('li');
            listItem.textContent = `${selectedCustomer}: ${customerData[selectedCustomer]} yêu cầu`;
            customerDataList.appendChild(listItem);
            totalCustomerRequests.textContent = `Tổng số yêu cầu: ${customerData[selectedCustomer]}`; // Hiển thị số yêu cầu của khách hàng đã chọn
        }
    }

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
                label: 'Số yêu cầu',
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
                            return `${tooltipItem.dataset.label}: ${tooltipItem.raw} yêu cầu`;
                        }
                    }
                }
            }
        }
    });

    function updateRequestTypeData() {
        const requestTypeDataList = document.getElementById('requestTypeDataList');
        const totalRequestTypeRequests = document.getElementById('totalRequestTypeRequests');
        requestTypeDataList.innerHTML = '';

        let totalRequests = 0;
        for (const [key, value] of Object.entries(initialData)) {
            const listItem = document.createElement('li');
            listItem.textContent = `${key}: ${value} yêu cầu`;
            requestTypeDataList.appendChild(listItem);
            totalRequests += value; // Tính tổng số yêu cầu
        }
        totalRequestTypeRequests.textContent = `Tổng số yêu cầu: ${totalRequests}`; // Hiển thị tổng số yêu cầu
    }

    async function filterByDates() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

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

        if (startDate && endDate) {
            await filterByDates();
            return;
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

        requestTypeChart.data.labels = Object.keys(filteredData);
        requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        requestTypeChart.update();
    }

    function updateChartWithFilteredData(filteredData) {
        requestTypeChart.data.labels = Object.keys(filteredData);
        requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        requestTypeChart.update();
    }

    // Đảm bảo rằng biểu đồ và số liệu được hiển thị khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        // Check for previously selected report in localStorage
        const savedReport = localStorage.getItem('selectedReport');
        if (savedReport) {
            document.getElementById('reportSelect').value = savedReport; // Set the dropdown to the saved value
        }
        showSelectedChart(); // Call the function to show the correct report

        // Update customer report data only if it's the customer report selected
        if (savedReport === 'customer') {
            updateCustomerReport(); // Update customer report data on page load
        }
    });

    //------------------------------Báo cáo phòng ban
    const departmentDataa = @json($departmentData);
    console.log(departmentDataa);

    // Check if departmentData is valid
    if (!departmentDataa || typeof departmentDataa !== 'object' || Object.keys(departmentDataa).length === 0) {
        console.error('No valid data available for the chart.');
    } else {
        // Extract labels and data for each status
        const labels = Object.keys(departmentDataa);

        const processingData = labels.map(department => departmentDataa[department]["Đang xử lý"] || 0);
        const notProcessedData = labels.map(department => departmentDataa[department]["Chưa xử lý"] || 0);
        const completedData = labels.map(department => departmentDataa[department]["Hoàn thành"] || 0);
        const canceledData = labels.map(department => departmentDataa[department]["Đã hủy"] || 0);

        const ctx = document.getElementById('departmentChart').getContext('2d');
        const departmentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Đang xử lý',
                        data: processingData,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    },
                    {
                        label: 'Chưa xử lý',
                        data: notProcessedData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    },
                    {
                        label: 'Hoàn thành',
                        data: completedData,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    },
                    {
                        label: 'Đã hủy',
                        data: canceledData,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    }
                ]
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
                                return `${tooltipItem.dataset.label}: ${tooltipItem.raw} yêu cầu`;
                            }
                        }
                    }
                }
            }
        });

        // Populate the statistics table
        const statisticsData = document.getElementById('departmentDataList');

        let totalProcessing = 0;
        let totalNotProcessed = 0;
        let totalCompleted = 0;
        let totalCanceled = 0;
        let totalRequests = 0; // Total requests variable

        labels.forEach(department => {
            const row = document.createElement('tr');
            const processing = departmentDataa[department]["Đang xử lý"] || 0;
            const notProcessed = departmentDataa[department]["Chưa xử lý"] || 0;
            const completed = departmentDataa[department]["Hoàn thành"] || 0;
            const canceled = departmentDataa[department]["Đã hủy"] || 0;

            // Update total counts
            totalProcessing += processing;
            totalNotProcessed += notProcessed;
            totalCompleted += completed;
            totalCanceled += canceled;

            // Calculate total requests for the department
            const totalForDepartment = processing + notProcessed + completed + canceled;
            totalRequests += totalForDepartment; // Accumulate total requests

            row.innerHTML = `
            <td>${department}</td>
            <td>${processing}</td>
            <td>${notProcessed}</td>
            <td>${completed}</td>
            <td>${canceled}</td>
        `;
            statisticsData.appendChild(row);
        });

        // Create a total row for status counts
        const totalRow = document.createElement('tr');
        totalRow.innerHTML = `
        <td><strong>Tổng:</strong></td>
        <td>${totalProcessing}</td>
        <td>${totalNotProcessed}</td>
        <td>${totalCompleted}</td>
        <td>${totalCanceled}</td>
    `;
        statisticsData.appendChild(totalRow);

        // Create a total row for all requests
        const totalRequestsRow = document.createElement('tr');
        totalRequestsRow.innerHTML = `
        <td><strong>Tổng số yêu cầu:</strong></td>
        <td colspan="4">${totalRequests}</td>
    `;
        statisticsData.appendChild(totalRequestsRow);
    }

    //----------------------------Báo cáo theo thời gian-------------------
    document.addEventListener('DOMContentLoaded', function () {
        const timeCtx = document.getElementById('timeReport')?.getContext('2d');
        if (!timeCtx) {
            console.error('Canvas element not found');
            return;
        }

        timeChart = new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Số yêu cầu theo thời gian',
                    data: [],
                    borderColor: '#8e44ad',
                    backgroundColor: 'rgba(142, 68, 173, 0.5)',
                    fill: true
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
                            label: function (tooltipItem) {
                                return `${tooltipItem.dataset.label}: ${tooltipItem.raw} yêu cầu`;
                            }
                        }
                    }
                }
            }
        });

        updateTimeReport('today'); // Tải dữ liệu mặc định
    });



    // Fetch data based on selected period
    async function fetchTimeData(period) {
        try {
            const response = await fetch(`http://localhost:8000/api/get-time-data?period=${period}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const timeData = await response.json();

            console.log('Fetched time data:', timeData); // Kiểm tra dữ liệu
            updateTimeChart(timeData); // Cập nhật biểu đồ với dữ liệu đã lấy
            updateTimeData(timeData); // Cập nhật số liệu tổng hợp
        } catch (error) {
            console.error('Error fetching time data:', error);
        }
    }


    // Update the time chart with fetched data
    let fetchedTimeData = {}; // Khởi tạo biến toàn cục

    function updateTimeChart(data, level = 'year') {
        let labels = [];
        let values = [];

        if (level === 'year') {
            labels = Object.keys(data);
            values = labels.map(year => data[year]?.total || 0);
        } else if (level === 'month') {
            const selectedYear = '2024'; // Thay bằng năm đã chọn
            const yearData = data[selectedYear] || { months: {} };
            labels = Object.keys(yearData.months);
            values = labels.map(month => yearData.months[month]?.total || 0);
        } else if (level === 'day') {
            const selectedYear = '2024'; // Thay bằng năm đã chọn
            const selectedMonth = '01'; // Thay bằng tháng đã chọn
            const monthData = data[selectedYear]?.months[selectedMonth] || { days: {} };
            labels = Object.keys(monthData.days);
            values = labels.map(day => monthData.days[day] || 0);
        }

        // Chỉ cập nhật dữ liệu biểu đồ
        timeChart.data.labels = labels;
        timeChart.data.datasets[0].data = values;
        timeChart.update();
    }


    // Handle button clicks to filter data
    function filterTimeBy(period) {
        fetchTimeData(period); // Lấy dữ liệu cho khoảng thời gian đã chọn
    }

    // Update the report based on the initial selection
    function updateTimeReport(defaultPeriod) {
        fetchTimeData(defaultPeriod).then((data) => {
            if (data) {
                updateTimeChart(data);
                updateTimeData(data); // Cập nhật số liệu tổng hợp
            } else {
                console.error('No data returned from API');
            }
        });
    }

    // Gọi updateTimeReport với giá trị mặc định
    updateTimeReport('today');


    //
    async function applyFilters() {
        const department = document.getElementById('departmentSelect').value;
        const status = document.getElementById('statusSelect').value;
        const requestType = document.getElementById('typeSelect').value;

        const params = new URLSearchParams();
        if (department) params.append('department', department);
        if (status) params.append('status', status);
        if (requestType) params.append('type', requestType);

        try {
            const response = await fetch(`http://localhost:8000/api/get-time-data?${params.toString()}`);
            if (!response.ok) throw new Error('Network response was not ok');

            const timeData = await response.json();
            updateTimeChart(timeData); // Cập nhật biểu đồ với dữ liệu đã lọc
            updateTimeData(timeData); // Cập nhật số liệu tổng hợp
        } catch (error) {
            console.error('Error fetching filtered time data:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchDepartments();
        fetchRequestTypes();
    });

    // Fetch departments and populate the dropdown
    async function fetchDepartments() {
        try {
            const response = await fetch('http://localhost:8000/api/get-departments');
            const departments = await response.json();
            const departmentSelect = document.getElementById('departmentSelect');
            departments.forEach(department => {
                const option = document.createElement('option');
                option.value = department.department_id;
                option.textContent = department.department_name;
                departmentSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching departments:', error);
        }
    }

    // Fetch request types and populate the dropdown
    async function fetchRequestTypes() {
        try {
            const response = await fetch('http://localhost:8000/api/get-request-types');
            const requestTypes = await response.json();
            const typeSelect = document.getElementById('typeSelect');
            requestTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.request_type_id;
                option.textContent = type.request_type_name;
                typeSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching request types:', error);
        }
    }

    function updateTimeData(timeData) {
        const totalRequests = Object.keys(timeData).reduce((acc, year) => {
            const yearData = timeData[year] || {};
            return acc + (yearData.total || 0);
        }, 0);

        // Cập nhật tổng số yêu cầu
        document.getElementById('totalTimeRequests').innerText = `Tổng số yêu cầu: ${totalRequests}`;

        // Cập nhật danh sách chi tiết
        const timeDataList = document.getElementById('timeDataList');
        timeDataList.innerHTML = ""; // Xóa nội dung cũ

        Object.keys(timeData).forEach(year => {
            const yearData = timeData[year] || { months: {} };
            const yearLi = document.createElement('li');
            yearLi.innerText = `${year}: ${yearData.total || 0} yêu cầu`;
            timeDataList.appendChild(yearLi);

            const monthUl = document.createElement('ul');
            Object.keys(yearData.months).forEach(month => {
                const monthData = yearData.months[month] || { days: {} };
                const monthLi = document.createElement('li');
                monthLi.innerText = `Tháng ${month}: ${monthData.total || 0} yêu cầu`;
                monthUl.appendChild(monthLi);

                const dayUl = document.createElement('ul');
                Object.keys(monthData.days).forEach(day => {
                    const dayCount = monthData.days[day] || 0;
                    const dayLi = document.createElement('li');
                    dayLi.innerText = `Ngày ${day}: ${dayCount} yêu cầu`;
                    dayUl.appendChild(dayLi);
                });
                monthLi.appendChild(dayUl);
            });
            yearLi.appendChild(monthUl);
        });
    }

    function handleLevelChange() {
        const level = document.getElementById('timeLevelSelect').value;
        updateTimeChart(fetchedTimeData, level); // fetchedTimeData là dữ liệu API đã tải
    }
</script>

</body>
</html>
