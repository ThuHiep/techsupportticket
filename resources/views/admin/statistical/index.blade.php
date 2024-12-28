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
            margin: 15px auto auto auto;
            transition: all 0.3s ease-in-out;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            font-size: 14px;
        }
        .report-select-container {
            text-align: center;
        }
        h1 {
            color: orange;
            text-align: left;
        }
        .report-section {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
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

        #combinedChart {
            width: 100% !important;
            height: 400px !important; /* Hoặc kích thước bạn muốn */
            display: block; /* Đảm bảo rằng canvas được hiển thị */
        }
        .suggestions-dropdown {
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            position: absolute; /* Adjust according to your layout */
            background-color: white;
            z-index: 1000;
        }

        .suggestions-dropdown div {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions-dropdown div:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="report-select-container">
        <h1>Báo cáo số lượng yêu cầu</h1>
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
        <div class="col-lg-7" id="chartContainer">
            <!--Biểu đồ khách hàng-->
            <div class="report-section" id="customerReportContainer" style="display: block;">
                <h3>Báo cáo theo khách hàng</h3>
                <div class="filter-container">
                    <div style="position: relative;">
                        <input type="text" id="customerNameInput" placeholder="Nhập tên khách hàng..." 
                               onkeyup="filterCustomers('name')" 
                               style="width: 100%; padding-right: 30px;">
                        <a href="{{ route('statistical.index') }}"
                           id="clearButton"
                           style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #D5D5D5; font-size: 14px; cursor: pointer; text-decoration: none;">
                           ✖
                        </a>
                    </div>
                    
                    <input type="text" id="customerIdInput" placeholder="Nhập mã khách hàng..." onkeyup="filterCustomers('id')">
                    <div id="suggestions" class="suggestions-dropdown" style="display: none;"></div>
                </div>
                <div class="chart-container">
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
                        @foreach ($departmentData as $department => $data)
                            <option value="{{ $department }}">{{ $department }}</option>
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
                    <button class="btn" onclick="showInput('dateSelection')">Chọn Ngày hoặc Khoảng Ngày</button>
                    <div id="dateSelectionInput" style="display: none;">
                        <div id="dateInput">
                            <label for="specificDate">Chọn ngày:</label>
                            <input type="date" id="specificDate" onchange="updateChartFromDate()">
                        </div>
                        <div id="dateRangeInput">
                            <label for="startDatee">Ngày bắt đầu:</label>
                            <input type="date" id="startDatee" onchange="updateChartFromDateRange()">
                            <label for="endDatee">Ngày kết thúc:</label>
                            <input type="date" id="endDatee" onchange="updateChartFromDateRange()">
                        </div>
                    </div>

{{--                    <button class="btn" onclick="showInput('week')">Chọn Tuần</button>--}}
{{--                    <div id="weekInput" style="display: none;">--}}
{{--                        <label for="specificWeek">Chọn tuần:</label>--}}
{{--                        <input type="week" id="specificWeek" onchange="updateChartFromWeek()">--}}
{{--                        <div id="weekRangeInput">--}}
{{--                            <label for="startWeek">Tuần bắt đầu:</label>--}}
{{--                            <input type="week" id="startWeek" onchange="updateChartFromWeekRange()">--}}
{{--                            <label for="endWeek">Tuần kết thúc:</label>--}}
{{--                            <input type="week" id="endWeek" onchange="updateChartFromWeekRange()">--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <button class="btn" onclick="showInput('monthSelection')">Chọn Tháng or Tháng Tháng</button>
                    <div id="monthSelectionInput" style="display: none;">
                        <div id="monthInput">
                            <label for="specificMonth">Chọn tháng:</label>
                            <input type="month" id="specificMonth" onchange="updateChartFromMonth()">
                        </div>

                        <div id="monthRangeInput">
                            <label for="startMonth">Tháng bắt đầu:</label>
                            <input type="month" id="startMonth" onchange="updateChartFromMonthRange()">

                            <label for="endMonth">Tháng kết thúc:</label>
                            <input type="month" id="endMonth" onchange="updateChartFromMonthRange()">
                        </div>
                    </div>

                    <button class="btn" onclick="showInput('yearSelection')">Năm or Năm</button>
                    <div id="yearSelectionInput" style="display: none;">
                        <div id="yearInput">
                            <label for="specificYear">Chọn năm:</label>
                            <select id="specificYear" onchange="updateChartFromYear()">
                                <option value="">--Chọn năm--</option>
                                <script>
                                    const currentYear = new Date().getFullYear();
                                    for (let i = currentYear - 10; i <= currentYear + 10; i++) {
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>
                        </div>

                        <div id="yearRangeInput">
                            <label for="startYear">Năm bắt đầu:</label>
                            <select id="startYear" onchange="updateChartFromYearRange()">
                                <option value="">--Chọn năm bắt đầu--</option>
                                <script>
                                    for (let i = 2020; i <= currentYear + 10; i++) { // Bắt đầu từ 2020
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>

                            <label for="endYear">Năm kết thúc:</label>
                            <select id="endYear" onchange="updateChartFromYearRange()">
                                <option value="">--Chọn năm kết thúc--</option>
                                <script>
                                    for (let i = 2020; i <= currentYear + 6; i++) { // Bắt đầu từ 2020
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>
                        </div>
                    </div>
                </div>
                <canvas id="combinedChart"></canvas>
            </div>
        </div>

        <!-- Cột phải - Số liệu cụ thể -->
        <div class="col-lg-5" id="dataContainer">
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
                <h3>Số liệu tổng hợp</h3>
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

        const customerReportContainer = document.getElementById('customerReportContainer');
        const requestTypeReportContainer = document.getElementById('requestTypeReportContainer');
        const departmentReportContainer = document.getElementById('departmentReportContainer');
        const timeReportContainer = document.getElementById('timeReportContainer');

        const customerDataContainer = document.getElementById('customerDataContainer');
        const requestTypeDataContainer = document.getElementById('requestTypeDataContainer');
        const departmentDataContainer = document.getElementById('departmentDataContainer');
        const timeDataContainer = document.getElementById('timeDataContainer');

        customerReportContainer.style.display = selectedReport === 'customer' ? 'block' : 'none';
        requestTypeReportContainer.style.display = selectedReport === 'requestType' ? 'block' : 'none';
        departmentReportContainer.style.display = selectedReport === 'department' ? 'block' : 'none';
        timeReportContainer.style.display = selectedReport === 'time' ? 'block' : 'none';

        customerDataContainer.style.display = selectedReport === 'customer' ? 'block' : 'none';
        requestTypeDataContainer.style.display = selectedReport === 'requestType' ? 'block' : 'none';
        departmentDataContainer.style.display = selectedReport === 'department' ? 'block' : 'none';
        timeDataContainer.style.display = selectedReport === 'time' ? 'block' : 'none';

        if (selectedReport === 'customer') {
            updateCustomerReport();
        } else if (selectedReport === 'requestType') {
            updateRequestTypeData();
        } else if (selectedReport === 'department') {
            updateDepartmentReport();
        } else if (selectedReport === 'time') {
            updateTimeReport('Ngày'); // Cập nhật dữ liệu thời gian, mặc định là hôm nay
        }
    }


    const customerIdMap = {};
    const customerColors = {};
    const customerData = [];

    @foreach($activeCustomers as $customer)
    customerData.push({
        full_name: '{{ $customer->full_name }}',
        customer_id: '{{ $customer->customer_id }}',
        requests_count: {{ $customer->requests_count }},
        color: '{{ $customerColors[$loop->index] }}' // Gán màu sắc riêng từng khách hàng
    });
    @endforeach


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
    //


    // Biểu đồ khách hàng
    // Biểu đồ khách hàng
    const customerCtx = document.getElementById('customerReport').getContext('2d');
    let customerChart = new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: Object.values(customerData).map(customer => customer.full_name), // Dùng full_name để hiển thị
            datasets: [{
                label: 'Số yêu cầu',
                data: Object.values(customerData).map(customer => customer.requests_count), // Dùng requests_count để hiển thị dữ liệu
                backgroundColor: Object.values(customerData).map(customer => customer.color) // Dùng color để hiển thị màu
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    function updateCustomerReport() {
        const nameInput = document.getElementById('customerNameInput').value.trim();
        const idInput = document.getElementById('customerIdInput').value.trim();
        let data = [];
        let labels = [];
        let colors = [];

        // If no input, show all customers
        if (nameInput === '' && idInput === '') {
            data = customerData.map(customer => customer.requests_count);
            labels = customerData.map(customer => customer.full_name);
            colors = customerData.map(customer => customer.color);
        } else if (idInput !== '') { // Filter by customer_id
            const matchedCustomer = customerData.find(customer => customer.customer_id === idInput);
            if (matchedCustomer) {
                data = [matchedCustomer.requests_count];
                labels = [matchedCustomer.full_name];
                colors = [matchedCustomer.color];
            } else {
                data = [];
                labels = [];
                colors = [];
            }
        } else if (nameInput !== '') { // Filter by full_name
            const filteredCustomers = customerData.filter(customer => customer.full_name === nameInput);
            if (filteredCustomers.length > 0) {
                data = filteredCustomers.map(customer => customer.requests_count);
                labels = filteredCustomers.map(customer => customer.full_name);
                colors = filteredCustomers.map(customer => customer.color);
            } else {
                data = [];
                labels = [];
                colors = [];
            }
        }

        // Update the chart
        customerChart.data.datasets[0].data = data;
        customerChart.data.labels = labels;
        customerChart.data.datasets[0].backgroundColor = colors;
        customerChart.update();

        // Display customer data
        displayCustomerData(idInput || nameInput || 'all');
    }

    const activeCustomers = @json($activeCustomers); // Pass customer data to JS

    function filterCustomers(type) {
        const suggestions = document.getElementById('suggestions');
        const nameInput = document.getElementById('customerNameInput').value.toLowerCase();
        const idInput = document.getElementById('customerIdInput').value.toLowerCase();
        const input = type === 'name' ? nameInput : idInput;

        // Clear previous suggestions
        suggestions.innerHTML = '';
        suggestions.style.display = 'none';

        if (input) {
            const filteredCustomers = activeCustomers.filter(customer => {
                return (type === 'name' && customer.full_name.toLowerCase().includes(input)) ||
                    (type === 'id' && customer.customer_id.toString().includes(input));
            });

            if (filteredCustomers.length > 0) {
                filteredCustomers.forEach(customer => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.textContent = `${customer.full_name} (${customer.customer_id})`; // Show both name and ID
                    suggestionItem.onclick = () => selectCustomer(customer); // Pass the entire customer object
                    suggestions.appendChild(suggestionItem);
                });
                suggestions.style.display = 'block'; // Show the suggestions
            }
        }
    }
    // Function to select a customer from suggestions
    function selectCustomer(customer) {
        document.getElementById('customerNameInput').value = customer.full_name; // Set name input
        document.getElementById('customerIdInput').value = customer.customer_id; // Set ID input
        document.getElementById('suggestions').style.display = 'none'; // Hide suggestions
        updateCustomerReport(customer); // Update the chart with the selected customer
    }

    window.onload = function() {
        displayCustomerData('all'); // Display data for all customers
        updateCustomerReport(); // Initialize the chart with all customers' data
    };

    function displayCustomerData(selectedCustomer) {
        const customerDataList = document.getElementById('customerDataList');
        const totalCustomerRequests = document.getElementById('totalCustomerRequests');
        customerDataList.innerHTML = '';

        if (selectedCustomer === 'all') {
            let totalRequests = 0;

            // Sort data by requests_count descending
            const sortedCustomers = customerData.sort((a, b) => b.requests_count - a.requests_count);

            sortedCustomers.forEach(customer => {
                const listItem = document.createElement('li');
                listItem.textContent = `${customer.full_name} (ID: ${customer.customer_id}): ${customer.requests_count} yêu cầu`;
                customerDataList.appendChild(listItem);
                totalRequests += customer.requests_count;
            });

            totalCustomerRequests.textContent = `Tổng số yêu cầu: ${totalRequests}`;
        } else {
            // Filter customers by full_name or customer_id
            const filteredCustomers = customerData.filter(customer =>
                customer.full_name === selectedCustomer || customer.customer_id === selectedCustomer
            );

            // Sort results by requests_count descending
            const sortedFilteredCustomers = filteredCustomers.sort((a, b) => b.requests_count - a.requests_count);

            sortedFilteredCustomers.forEach(customer => {
                const listItem = document.createElement('li');
                listItem.textContent = `${customer.full_name} (ID: ${customer.customer_id}): ${customer.requests_count} yêu cầu`;
                customerDataList.appendChild(listItem);
            });
        }
    }


    ///////////////////////////////////////////////////////////////////////////////
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
        const labels = Object.keys(departmentDataa);
        let departmentChart;

        function updateChart(selectedDepartment) {
            const filteredData = selectedDepartment === 'all' ? departmentDataa : { [selectedDepartment]: departmentDataa[selectedDepartment] };
            const filteredLabels = selectedDepartment === 'all' ? labels : [selectedDepartment];

            // Prepare data for the chart
            const processingData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Đang xử lý"]) || 0);
            const notProcessedData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Chưa xử lý"]) || 0);
            const completedData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Hoàn thành"]) || 0);
            const canceledData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Đã hủy"]) || 0);

            const ctx = document.getElementById('departmentChart').getContext('2d');

            // Clear existing chart if it exists
            if (departmentChart) {
                departmentChart.destroy();
            }

            // Create a new chart
            departmentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filteredLabels, // Show all departments
                    datasets: [
                        { label: 'Đang xử lý', data: processingData, backgroundColor: 'rgba(75, 192, 192, 0.5)' },
                        { label: 'Chưa xử lý', data: notProcessedData, backgroundColor: 'rgba(54, 162, 235, 0.5)' },
                        { label: 'Hoàn thành', data: completedData, backgroundColor: 'rgba(153, 102, 255, 0.5)' },
                        { label: 'Đã hủy', data: canceledData, backgroundColor: 'rgba(255, 99, 132, 0.5)' }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { position: 'top' },
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

            // Update the statistics table
            const statisticsData = document.getElementById('departmentDataList');
            statisticsData.innerHTML = ''; // Clear existing rows

            let totalProcessing = 0;
            let totalNotProcessed = 0;
            let totalCompleted = 0;
            let totalCanceled = 0;

            // Filter for the statistics table
            filteredLabels.forEach(department => {
                const data = filteredData[department];
                const processing = data ? data["Đang xử lý"] || 0 : 0;
                const notProcessed = data ? data["Chưa xử lý"] || 0 : 0;
                const completed = data ? data["Hoàn thành"] || 0 : 0;
                const canceled = data ? data["Đã hủy"] || 0 : 0;

                // Only add the row if there is at least one non-zero status
                if (processing > 0 || notProcessed > 0 || completed > 0 || canceled > 0) {
                    const row = document.createElement('tr');

                    // Update total counts
                    totalProcessing += processing;
                    totalNotProcessed += notProcessed;
                    totalCompleted += completed;
                    totalCanceled += canceled;

                    row.innerHTML = `
                        <td>${department}</td>
                        <td>${processing}</td>
                        <td>${notProcessed}</td>
                        <td>${completed}</td>
                        <td>${canceled}</td>
                    `;
                    statisticsData.appendChild(row);
                }
            });

            // Create total row for status counts
            // Create total row for status counts
            const totalRow = document.createElement('tr');
            totalRow.innerHTML = `
                <td><strong>Tổng:</strong></td>
                <td><strong>${totalProcessing}</strong></td>
                <td><strong>${totalNotProcessed}</strong></td>
                <td><strong>${totalCompleted}</strong></td>
                <td><strong>${totalCanceled}</strong></td>
            `;
            statisticsData.appendChild(totalRow);
        }

        // Initialize chart and table on page load
        updateChart('all');

        // Event listener for department filter
        document.getElementById('departmentFilter').addEventListener('change', function() {
            updateChart(this.value);
        });
    }



    //----------------------------Báo cáo theo thời gian-------------------
    // Biểu đồ thời gian
    let combinedChart;

    function updateTimeReport(period, filteredData) {
        console.log("Filtered Data:", filteredData);
        const labels = filteredData.map(item => item.period);
        const datasets = [{
            label: 'Đang xử lý',
            data: filteredData.map(item => item.total?.['Đang xử lý']),
            backgroundColor: 'rgba(75, 192, 192, 0.5)'
        }, {
            label: 'Chưa xử lý',
            data: filteredData.map(item => item.total?.['Chưa xử lý']),
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }, {
            label: 'Hoàn thành',
            data: filteredData.map(item => item.total?.['Hoàn thành']),
            backgroundColor: 'rgba(153, 102, 255, 0.5)'
        }, {
            label: 'Đã hủy',
            data: filteredData.map(item => item.total?.['Đã hủy']),
            backgroundColor: 'rgba(255, 99, 132, 0.5)'
        }];

        if (combinedChart) {
            combinedChart.data.labels = labels;
            combinedChart.data.datasets = datasets;
            combinedChart.update();
        } else {
            const ctx = document.getElementById('combinedChart').getContext('2d');
            combinedChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { position: 'top' },
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
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const yearInput = document.getElementById('specificYear');
        if (yearInput) {
            const currentYear = new Date().getFullYear(); // Lấy năm hiện tại
            yearInput.value = currentYear; // Gán giá trị năm hiện tại vào input
            updateChartFromYear(); // Cập nhật biểu đồ theo năm
        }
    });
    function displayTotalSummary(filteredData) {
        const totalTimeRequests = document.getElementById('totalTimeRequests');
        let totalProcessing = 0;
        let totalPending = 0;
        let totalCompleted = 0;
        let totalCancelled = 0;

        filteredData.forEach(item => {
            totalProcessing += item.total['Đang xử lý'] || 0;
            totalPending += item.total['Chưa xử lý'] || 0;
            totalCompleted += item.total['Hoàn thành'] || 0;
            totalCancelled += item.total['Đã hủy'] || 0;
        });

        totalTimeRequests.innerHTML = `
        <strong>Tổng số liệu:</strong><br>
        Đang xử lý: ${totalProcessing}<br>
        Chưa xử lý: ${totalPending}<br>
        Hoàn thành: ${totalCompleted}<br>
        Đã hủy: ${totalCancelled}
    `;
    }

    function updateChartFromDate() {
        const date = document.getElementById('specificDate').value; // Lấy ngày từ input
        const timeData = @json($timeData); // Đảm bảo dữ liệu timeData có sẵn ở đây

        console.log(date);

        // Lọc dữ liệu cho ngày cụ thể
        const filteredData = timeData['Ngày'].filter(item => item.period === date);

        // Cập nhật biểu đồ với dữ liệu đã lọc
        if (filteredData.length > 0) {
            updateChartWithFilteredDataa(filteredData); // Cập nhật biểu đồ với dữ liệu cho ngày hôm nay
            displayTotalSummary(filteredData);
        } else {
            alert('Không có dữ liệu cho ngày này.'); // Thông báo nếu không có dữ liệu
        }
    }

    // Hàm để lấy tuần hiện tại
    function getCurrentWeek() {
        const today = new Date();
        const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
        const daysInFirstWeek = ((firstDayOfYear.getDay() + 6) % 7);
        const weekNumber = Math.ceil(((today - firstDayOfYear) / 86400000 + daysInFirstWeek) / 7);
        return {
            year: today.getFullYear(),
            week: weekNumber.toString().padStart(2, '0') // Current week as a 2-digit string
        };
    }

    function updateChartFromWeek() {
        const { year, week } = getCurrentWeek(); // Lấy tuần hiện tại
        const periodToCheck = `${year}-${week}`;
        const timeData = @json($timeData); // Dữ liệu gốc

        // Lọc dữ liệu cho tuần hiện tại
        const filteredData = timeData['Tuần'].filter(item => {
            if (!item.period) {
                console.warn("Missing period for item:", item);
                return false; // Skip items with no period
            }
            return item.period.trim() === periodToCheck;
        });

        console.log("Filtered Data for Week:", filteredData); // Kiểm tra dữ liệu đã lọc

        if (filteredData.length > 0) {
            updateTimeReport('Tuần', filteredData); // Cập nhật biểu đồ với dữ liệu cho tuần hiện tại
            displayTotalSummary(filteredData);
        } else {
            alert('Không có dữ liệu cho tuần hiện tại.'); // Thông báo nếu không có dữ liệu
        }
    }

    function updateChartFromMonth() {
        const selectedMonth = document.getElementById('specificMonth').value; // Lấy tháng từ input
        const [year, month] = selectedMonth.split('-'); // Tách năm và tháng

        const timeData = @json($timeData); // Dữ liệu gốc

        console.log('Selected Month:', month); // Debugging
        console.log('Selected Year:', year); // Debugging

        // Lọc dữ liệu cho tháng được chọn
        const filteredData = timeData['Tháng'].filter(item => item.period === parseInt(month));

        console.log('Filtered data:', filteredData); // Debugging

        if (filteredData.length > 0) {
            updateTimeReport('Tháng', filteredData); // Cập nhật biểu đồ cho tháng được chọn
            displayTotalSummary(filteredData);
        } else {
            alert(`Không có dữ liệu cho tháng ${month} năm ${year}.`);
        }
    }

    function updateChartFromYear() {
        const year = parseInt(document.getElementById('specificYear').value);
        const timeData = @json($timeData);

        console.log('Selected Year:', year); // Kiểm tra giá trị năm
        console.log('Time Data:', timeData); // Kiểm tra toàn bộ dữ liệu

        // Lọc dữ liệu theo năm
        const filteredData = timeData['Năm'].filter(item => item.period === year);

        console.log('Filtered Data:', filteredData); // Kiểm tra dữ liệu đã lọc

        if (filteredData.length > 0) {
            updateChartWithFilteredDataa(filteredData);
            displayTotalSummary(filteredData);
        } else {
            alert('Không có dữ liệu cho năm này.');
        }
    }

    // Cập nhật biểu đồ với dữ liệu đã lọc
    function updateChartWithFilteredDataa(data) {
        console.log("Cập nhật biểu đồ với dữ liệu đã lọc:", data);
        const labels = data.map(item => item.period);
        const datasets = [
            {
                label: 'Đang xử lý',
                data: data.map(item => item.total['Đang xử lý'] || 0),
                backgroundColor: 'rgba(75, 192, 192, 0.5)'
            },
            {
                label: 'Chưa xử lý',
                data: data.map(item => item.total['Chưa xử lý'] || 0),
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            },
            {
                label: 'Hoàn thành',
                data: data.map(item => item.total['Hoàn thành'] || 0),
                backgroundColor: 'rgba(153, 102, 255, 0.5)'
            },
            {
                label: 'Đã hủy',
                data: data.map(item => item.total['Đã hủy'] || 0),
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }
        ];

        // Cập nhật biểu đồ
        if (combinedChart) {
            combinedChart.data.labels = labels;
            combinedChart.data.datasets = datasets;
            combinedChart.update();
        } else {
            const ctx = document.getElementById('combinedChart').getContext('2d');
            combinedChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { position: 'top' },
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
        }

        // Display the total summary
        displayTotalSummary(data);
    }

    function showInput(type) {
        // Ẩn tất cả các div nhập liệu
        document.getElementById('dateInput').style.display = 'none';
        //document.getElementById('weekInput').style.display = 'none';
        document.getElementById('monthInput').style.display = 'none';
        document.getElementById('yearInput').style.display = 'none';
        document.getElementById('monthRangeInput').style.display = 'none';
        document.getElementById('yearSelectionInput').style.display = 'none';
        document.getElementById('dateSelectionInput').style.display = 'none';
        // Hiển thị div tương ứng với button được nhấn
        if (type === 'dateSelection') {
            const dateSelectionDiv = document.getElementById('dateSelectionInput');
            dateSelectionDiv.style.display = dateSelectionDiv.style.display === 'block' ? 'none'    : 'block';
            document.getElementById('dateInput').style.display = 'block'; // Hiển thị ô lọc ngày
            document.getElementById('dateRangeInput').style.display = 'block'; // Hiển thị ô lọc khoảng ngày
        }else if (type === 'yearSelection') {
            const yearSelectionDiv = document.getElementById('yearSelectionInput');
            yearSelectionDiv.style.display = yearSelectionDiv.style.display === 'block' ? 'none' : 'block';
            document.getElementById('yearInput').style.display = 'block'; // Hiển thị ô lọc năm
            document.getElementById('yearRangeInput').style.display = 'block'; // Hiển thị ô lọc khoảng năm
        }else if (type === 'monthSelection') {
            const monthSelectionDiv = document.getElementById('monthSelectionInput');
            monthSelectionDiv.style.display = monthSelectionDiv.style.display === 'block' ? 'none' : 'block';
            document.getElementById('monthInput').style.display = 'block'; // Hiển thị ô lọc tháng
            document.getElementById('monthRangeInput').style.display = 'block'; // Hiển thị ô lọc khoảng tháng
        }else if (type === 'date') {
            document.getElementById('dateInput').style.display = 'block';
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('specificDate').value = today;
            updateChartFromDate(); // Cập nhật biểu đồ cho ngày hôm nay
        // } else if (type === 'week') {
        //     document.getElementById('weekInput').style.display = 'block';
        //     const currentWeek = getCurrentWeek();
        //     document.getElementById('specificWeek').value = currentWeek;
        //     updateChartFromWeek(); // Cập nhật biểu đồ cho tuần hiện tại
        } else if (type === 'month') {
            document.getElementById('monthInput').style.display = 'block';
            const currentMonth = new Date().toISOString().slice(0, 7);
            document.getElementById('specificMonth').value = currentMonth;
            updateChartFromMonth(); // Cập nhật biểu đồ cho tháng hiện tại ngay lập tức
        } else if (type === 'year') {
            document.getElementById('yearInput').style.display = 'block';
            const currentYear = new Date().getFullYear();
            document.getElementById('specificYear').value = currentYear;
            updateChartFromYear(); // Cập nhật biểu đồ cho năm hiện tại
        } else if (type === 'monthRange') {
            document.getElementById('monthRangeInput').style.display = 'block';
            // Không cần thiết phải cập nhật biểu đồ ngay lập tức khi chọn khoảng tháng
        } else if (type === 'yearRange') { // Thêm trường hợp cho khoảng năm
            document.getElementById('yearRangeInput').style.display = 'block';
            // Không cần thiết phải cập nhật biểu đồ ngay lập tức khi chọn khoảng năm
        }
    }

    // Hàm để lấy tuần hiện tại
    // function getCurrentWeek() {
    //     const today = new Date();
    //     const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
    //     const daysInFirstWeek = ((firstDayOfYear.getDay() + 6) % 7);
    //     const weekNumber = Math.ceil(((today - firstDayOfYear) / 86400000 + daysInFirstWeek) / 7);
    //     return `${today.getFullYear()}-W${weekNumber.toString().padStart(2, '0')}`;
    // }

    function updateChartFromDateRange() {
        console.log('Hàm được gọi'); // Kiểm tra xem hàm có được gọi không
        const startDatee = document.getElementById('startDatee').value;
        const endDatee = document.getElementById('endDatee').value;

        console.log("Start Date:", startDatee);
        console.log("End Date:", endDatee);

        if (!startDatee || !endDatee) {
            alert('Vui lòng nhập đầy đủ ngày bắt đầu và ngày kết thúc.');
            return;
        }

        // Giả sử bạn có dữ liệu mẫu
        const timeData = @json($timeData);

        // Chuyển đổi ngày thành đối tượng Date
        const start = new Date(startDatee);
        const end = new Date(endDatee);
        end.setDate(end.getDate() + 1); // Bao gồm ngày kết thúc

        // Lọc dữ liệu cho khoảng thời gian
        const filteredData = timeData['Ngày'].filter(item => {
            const itemDate = new Date(item.period);
            return itemDate >= start && itemDate < end;
        });

        console.log("Filtered Data:", filteredData);

        // Cập nhật biểu đồ với dữ liệu đã lọc
        if (filteredData.length > 0) {
            updateTimeReport('Khoảng ngày', filteredData);
            displayTotalSummary(filteredData);
        } else {
            alert('Không có dữ liệu trong khoảng ngày này.');
        }
    }

    function updateChartFromWeekRange() {
        const startWeek = document.getElementById('startWeek').value; // e.g., "2024-W49"
        const endWeek = document.getElementById('endWeek').value; // e.g., "2024-W52"
        const timeData = @json($timeData);

        console.log("Start Week:", startWeek);
        console.log("End Week:", endWeek);

        if (!startWeek || !endWeek) {
            alert('Vui lòng chọn cả tuần bắt đầu và tuần kết thúc.');
            return;
        }

        // Lọc dữ liệu tuần
        const filteredData = timeData['Tuần'].filter(item => {
            return item.period.localeCompare(startWeek) >= 0 &&
                item.period.localeCompare(endWeek) <= 0;
        });

        console.log("Filtered Data:", filteredData);

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng tuần', filteredData);
            displayTotalSummary(filteredData);
        } else {
            alert('Không có dữ liệu trong khoảng tuần được chọn.');
        }
    }

    function updateChartFromMonthRange() {
        const startMonthValue = document.getElementById('startMonth').value; // Tháng bắt đầu
        const endMonthValue = document.getElementById('endMonth').value; // Tháng kết thúc

        if (!startMonthValue || !endMonthValue) {
            alert('Vui lòng chọn cả tháng bắt đầu và tháng kết thúc.');
            return;
        }

        const [startYear, startMonth] = startMonthValue.split('-');
        const [endYear, endMonth] = endMonthValue.split('-');

        const timeData = @json($timeData); // Dữ liệu gốc

        console.log('Start Month:', startMonth, 'Year:', startYear);
        console.log('End Month:', endMonth, 'Year:', endYear);

        // Lọc dữ liệu trong khoảng tháng
        const filteredData = timeData['Tháng'].filter(item => {
            const itemMonth = item.period; // Tháng từ dữ liệu
            const itemYear = parseInt(startYear); // Giả định năm dựa trên input tháng

            // Kiểm tra xem item nằm trong khoảng tháng
            const isInStartRange = (itemYear > parseInt(startYear) || (itemYear === parseInt(startYear) && itemMonth >= parseInt(startMonth)));
            const isInEndRange = (itemYear < parseInt(endYear) || (itemYear === parseInt(endYear) && itemMonth <= parseInt(endMonth)));

            return isInStartRange && isInEndRange;
        });

        console.log('Filtered data:', filteredData);

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng tháng', filteredData);
            displayTotalSummary(filteredData);
        } else {
            alert(`Không có dữ liệu cho khoảng thời gian từ tháng ${startMonth} năm ${startYear} đến tháng ${endMonth} năm ${endYear}.`);
        }
    }

    function updateChartFromYearRange() {
        const startYearValue = document.getElementById('startYear').value; // Năm bắt đầu
        const endYearValue = document.getElementById('endYear').value; // Năm kết thúc

        if (!startYearValue || !endYearValue) {
            alert('Vui lòng chọn cả năm bắt đầu và năm kết thúc.');
            return;
        }

        const timeData = @json($timeData); // Dữ liệu gốc

        console.log('Start Year:', startYearValue);
        console.log('End Year:', endYearValue);
        console.log('Original Data:', timeData['Năm']); // Kiểm tra dữ liệu gốc

        // Lọc dữ liệu trong khoảng năm từ mảng "Năm"
        const filteredData = timeData['Năm'].filter(item => {
            const itemYear = item.period; // Năm từ dữ liệu
            return itemYear >= parseInt(startYearValue) && itemYear <= parseInt(endYearValue);
        });

        console.log('Filtered data:', filteredData); // In ra dữ liệu đã lọc

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng năm', filteredData);
            displayTotalSummary(filteredData);
        } else {
            alert(`Không có dữ liệu cho khoảng thời gian từ năm ${startYearValue} đến năm ${endYearValue}.`);
        }
    }
</script>

</body>
</html>
