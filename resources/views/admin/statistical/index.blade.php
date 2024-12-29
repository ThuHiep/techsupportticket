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
            <div class="report-section" id="requestTypeReportContainer" style="display: block;">
                <h3>Báo cáo theo loại yêu cầu</h3>
                <div class="filter-container">
                    <label for="requestTypeSelect"></label>
                    <select id="requestTypeSelect" onchange="updateChartBasedOnSelection()">
                        <option value="all">Tất cả loại yêu cầu</option>
                        @foreach (array_keys($requestTypeData) as $requestType)
                            <option value="{{ $requestType }}">{{ $requestType }}</option>
                        @endforeach
                    </select>
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
                    <button class="btn" onclick="showInput('dateSelection')">Ngày</button>
                    <div id="dateSelectionInput" style="display: none;">
                        <div id="dateRangeInput">
                            <label for="startDatee">Ngày bắt đầu:</label>
                            <input type="date" id="startDatee" onchange="updateChartFromDateRange()">
                            <label for="endDatee">Ngày kết thúc:</label>
                            <input type="date" id="endDatee" onchange="updateChartFromDateRange()">
                        </div>
                    </div>
                    <button class="btn" onclick="showInput('monthSelection')">Chọn Tháng</button>
                    <div id="monthSelectionInput" style="display: none;">
                        <div id="monthRangeInput" style="display: block;">
                            <label for="startMonth">Tháng bắt đầu:</label>
                            <input type="month" id="startMonth" onchange="updateChartFromMonthRange()">
                            <label for="endMonth">Tháng kết thúc:</label>
                            <input type="month" id="endMonth" onchange="updateChartFromMonthRange()">
                        </div>
                    </div>

                    <button class="btn" onclick="showInput('yearSelection')">Năm</button>
                    <div id="yearSelectionInput" style="display: none;">
                        <div id="yearRangeInput">
                            <label for="startYear">Năm bắt đầu:</label>
                            <select id="startYear" onchange="updateChartFromYearRange()">
                                <option value="">--Chọn năm bắt đầu--</option>
                                <script>
                                    const currentYear = new Date().getFullYear();
                                    for (let i = 2020; i <= currentYear + 10; i++) {
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>

                            <label for="endYear">Năm kết thúc:</label>
                            <select id="endYear" onchange="updateChartFromYearRange()">
                                <option value="">--Chọn năm kết thúc--</option>
                                <script>
                                    for (let i = 2020; i <= currentYear + 10; i++) {
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
            <!--Số liệu loại yêu cầu-------------->
            <div class="report-section" id="requestTypeDataContainer" style="display: none;">
                <h3>Số liệu tổng hợp</h3>
                <table style="border-collapse: collapse; width: 100%; font-size: 13px;">
                    <thead>
                    <tr>
                        <th style="padding: 5px">Loại yêu cầu</th>
                        <th style="padding: 5px">Đang xử lý</th>
                        <th style="padding: 5px">Chưa xử lý</th>
                        <th style="padding: 5px">Hoàn thành</th>
                        <th style="padding: 5px">Đã hủy</th>
                        <th style="padding: 5px">Tổng</th>
                    </tr>
                    </thead>
                    <tbody id="requestTypeDataList"></tbody>
                </table>
                <p id="totalRequestTypeRequests"></p>
            </div>
            <!--Số liệu phòng ban-->
            <div class="report-section" id="departmentDataContainer" style="display: none;">
                <h3>Số liệu tổng hợp</h3>
                <table style="border-collapse: collapse; width: 100%; font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="padding: 5px">Phòng ban</th>
                            <th style="padding: 5px">Đang xử lý</th>
                            <th style="padding: 5px">Chưa xử lý</th>
                            <th style="padding: 5px">Hoàn thành</th>
                            <th style="padding: 5px">Đã hủy</th>
                            <th style="padding: 5px ">Tổng</th>
                        </tr>
                    </thead>
                    <tbody id="departmentDataList"></tbody>
                </table>
                <p id="totalDepartmentRequests"></p>
            </div>
            <!--Số liệu thời gian-->
            <div class="report-section" id="timeDataContainer" style="display: none;">
                <h3 id="summaryTitle">Số liệu tổng hợp</h3>
                <table style="border-collapse: collapse; width: 100%; font-size: 13px;">
                    <thead>
                    <tr>
                        <th style="padding: 5px">Thời gian</th>
                        <th style="padding: 5px">Đang xử lý</th>
                        <th style="padding: 5px">Chưa xử lý</th>
                        <th style="padding: 5px">Hoàn thành</th>
                        <th style="padding: 5px">Đã hủy</th>
                        <th style="padding: 5px">Tổng</th>
                    </tr>
                    </thead>
                    <tbody id="timeDataList"></tbody>
                </table>
                <p id="totalTimeRequests"></p>
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
            updateTimeReport(); // Cập nhật dữ liệu thời gian, mặc định là hôm nay
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
    const initialData = @json($requestTypeData);
    let currentChart = null; // Biến để giữ biểu đồ hiện tại

    function drawRequestTypeChart(data) {
        const ctx = document.getElementById('requestTypeChart').getContext('2d');

        // Nếu biểu đồ đã tồn tại, hủy nó trước khi vẽ lại
        if (currentChart) {
            currentChart.destroy();
        }

        const labels = Object.keys(data);
        const statuses = ['Đang xử lý', 'Chưa xử lý', 'Hoàn thành', 'Đã hủy'];

        const backgroundColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
        ];

        const datasets = statuses.map((status, index) => ({
            label: status,
            data: labels.map(label => (data[label] && data[label][status]) ? data[label][status] : 0),
            backgroundColor: backgroundColors[index],
            borderColor: backgroundColors[index],
            borderWidth: 1
        }));

        currentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Loại yêu cầu' },
                        ticks: { autoSkip: false }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Số lượng yêu cầu' }
                    }
                },
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

    function updateChartRequestType() {
        const data = initialData; // Sử dụng dữ liệu từ biến PHP
        drawRequestTypeChart(data);
        updateRequestTypeData(); // Cập nhật số liệu tổng hợp
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateChartRequestType();
        populateRequestTypeSelect();

        // Kiểm tra lựa chọn đã lưu trong localStorage
        const savedType = localStorage.getItem('selectedRequestType') || 'all';
        document.getElementById('requestTypeSelect').value = savedType;
        updateChartBasedOnSelection(); // Cập nhật dữ liệu dựa trên lựa chọn đã lưu
    });

    function updateRequestTypeData(selectedType = 'all') {
        const requestTypeDataList = document.getElementById('requestTypeDataList');
        const totalRequestTypeRequests = document.getElementById('totalRequestTypeRequests');
        requestTypeDataList.innerHTML = '';

        let totalRequests = 0;

        // Lọc dữ liệu theo loại yêu cầu
        const dataToDisplay = selectedType === 'all' ? initialData : { [selectedType]: initialData[selectedType] };

        for (const [typeName, statuses] of Object.entries(dataToDisplay)) {
            const listItem = document.createElement('tr');
            const requestCount = Object.values(statuses).reduce((a, b) => a + b, 0); // Tính tổng số yêu cầu cho loại này

            listItem.innerHTML = `
            <td style="padding: 5px">${typeName}</td>
            <td style="padding: 5px">${statuses['Đang xử lý'] || 0}</td>
            <td style="padding: 5px">${statuses['Chưa xử lý'] || 0}</td>
            <td style="padding: 5px">${statuses['Hoàn thành'] || 0}</td>
            <td style="padding: 5px">${statuses['Đã hủy'] || 0}</td>
            <td style="padding: 5px"><strong>${requestCount}</strong></td>
        `;

            requestTypeDataList.appendChild(listItem);
            totalRequests += requestCount; // Tính tổng số yêu cầu
        }

        totalRequestTypeRequests.innerHTML = `<strong>Tổng số yêu cầu:</strong> <strong>${totalRequests}</strong>`; // In đậm "Tổng" và số yêu cầu
    }

    // Hàm để thêm các loại yêu cầu vào dropdown
    function populateRequestTypeSelect() {
        const selectElement = document.getElementById('requestTypeSelect');

        // Xóa tất cả các tùy chọn hiện tại (nếu có)
        selectElement.innerHTML = '<option value="all">Tất cả</option>';

        for (const typeName of Object.keys(initialData)) {
            const option = document.createElement('option');
            option.value = typeName;
            option.textContent = typeName;
            selectElement.appendChild(option);
        }
    }

    // Hàm để cập nhật biểu đồ dựa trên lựa chọn
    function updateChartBasedOnSelection() {
        const selectedType = document.getElementById('requestTypeSelect').value;
        const dataToDisplay = selectedType === 'all' ? initialData : { [selectedType]: initialData[selectedType] };

        drawRequestTypeChart(dataToDisplay); // Gọi hàm vẽ biểu đồ với dữ liệu đã lọc
        updateRequestTypeData(selectedType); // Cập nhật số liệu tương ứng
        localStorage.setItem('selectedRequestType', selectedType); // Lưu lựa chọn vào localStorage
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

            // Chuẩn bị dữ liệu cho biểu đồ
            const processingData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Đang xử lý"]) || 0);
            const notProcessedData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Chưa xử lý"]) || 0);
            const completedData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Hoàn thành"]) || 0);
            const canceledData = filteredLabels.map(department => (filteredData[department] && filteredData[department]["Đã hủy"]) || 0);

            const ctx = document.getElementById('departmentChart').getContext('2d');

            // Xóa biểu đồ hiện tại nếu tồn tại
            if (departmentChart) {
                departmentChart.destroy();
            }

            // Tạo biểu đồ mới
            departmentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filteredLabels, // Hiển thị các phòng ban được chọn
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

            // Cập nhật bảng thống kê chỉ hiển thị số liệu của phòng ban được chọn
            const statisticsData = document.getElementById('departmentDataList');
            statisticsData.innerHTML = ''; // Xóa các hàng hiện tại

            let totalProcessing = 0;
            let totalNotProcessed = 0;
            let totalCompleted = 0;
            let totalCanceled = 0;

            // Duyệt qua các phòng ban được lọc để hiển thị trong bảng
            filteredLabels.forEach(department => {
                const data = filteredData[department] || { "Đang xử lý": 0, "Chưa xử lý": 0, "Hoàn thành": 0, "Đã hủy": 0 };
                const processing = data["Đang xử lý"] || 0;
                const notProcessed = data["Chưa xử lý"] || 0;
                const completed = data["Hoàn thành"] || 0;
                const canceled = data["Đã hủy"] || 0;
                const total = processing + notProcessed + completed + canceled;

                // Cập nhật tổng số liệu
                totalProcessing += processing;
                totalNotProcessed += notProcessed;
                totalCompleted += completed;
                totalCanceled += canceled;

                // Thêm hàng cho từng phòng ban
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${department}</td>
            <td>${processing}</td>
            <td>${notProcessed}</td>
            <td>${completed}</td>
            <td>${canceled}</td>
            <td><strong>${total}</strong></td>
        `;
                statisticsData.appendChild(row);
            });

            // Tạo hàng tổng chỉ cho các phòng ban được lọc
            const totalRow = document.createElement('tr');
            const grandTotal = totalProcessing + totalNotProcessed + totalCompleted + totalCanceled;
            totalRow.innerHTML = `
        <td><strong>Tổng:</strong></td>
        <td><strong>${totalProcessing}</strong></td>
        <td><strong>${totalNotProcessed}</strong></td>
        <td><strong>${totalCompleted}</strong></td>
        <td><strong>${totalCanceled}</strong></td>
        <td><strong>${grandTotal}</strong></td>
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
        const yearInput = document.getElementById('startYear');
        if (yearInput) {
            const currentYear = new Date().getFullYear(); // Lấy năm hiện tại
            yearInput.value = currentYear; // Gán giá trị năm hiện tại vào input
            updateChartFromYear(); // Cập nhật biểu đồ theo năm
        }
    });
    function updateChartFromYear() {
        const year = parseInt(document.getElementById('startYear').value);
        const timeData = @json($timeData);

        console.log('Selected Year:', year); // Kiểm tra giá trị năm
        console.log('Time Data:', timeData); // Kiểm tra toàn bộ dữ liệu

        // Lọc dữ liệu theo năm
        const filteredData = timeData['Năm'].filter(item => item.period === year);

        console.log('Filtered Data:', filteredData); // Kiểm tra dữ liệu đã lọc

        if (filteredData.length > 0) {
            updateTimeReport('year', filteredData); // Pass 'year' as period
            displayTotalSummaryByTime(filteredData);
        } else {
            alert('Không có dữ liệu cho năm này.');
        }
    }

    function displayTotalSummaryByTime(filteredData, selectedPeriodType) {
        const timeDataList = document.getElementById('timeDataList');
        const totalTimeRequests = document.getElementById('totalTimeRequests');

        // Clear previous data
        timeDataList.innerHTML = '';

        let totalProcessing = 0;
        let totalPending = 0;
        let totalCompleted = 0;
        let totalCancelled = 0;

        // Populate the table and calculate totals
        filteredData.forEach(item => {
            const processing = item.total['Đang xử lý'] || 0;
            const pending = item.total['Chưa xử lý'] || 0;
            const completed = item.total['Hoàn thành'] || 0;
            const cancelled = item.total['Đã hủy'] || 0;

            totalProcessing += processing;
            totalPending += pending;
            totalCompleted += completed;
            totalCancelled += cancelled;

            timeDataList.innerHTML += `
            <tr>
                <td style="padding: 5px">${item.period}</td>
                <td style="padding: 5px">${processing}</td>
                <td style="padding: 5px">${pending}</td>
                <td style="padding: 5px">${completed}</td>
                <td style="padding: 5px">${cancelled}</td>
                <td style="padding: 5px"><strong>${processing + pending + completed + cancelled}</strong></td>
            </tr>
        `;
        });

        // Get the first and last periods from the filtered data for the range
        const firstPeriod = filteredData[0].period; // Assuming period is formatted as "YYYY"
        const lastPeriod = filteredData[filteredData.length - 1].period;
        // Format the output based on whether the years are the same
        let totalRequestsText;
        if (firstPeriod === lastPeriod) {
            totalRequestsText = `Tổng số yêu cầu trong năm ${firstPeriod} là: ${totalProcessing + totalPending + totalCompleted + totalCancelled}`;
        } else {
            totalRequestsText = `Tổng số yêu cầu từ ${firstPeriod} đến ${lastPeriod} là: ${totalProcessing + totalPending + totalCompleted + totalCancelled}`;
        }

        // Update the total requests display with the specific range
        totalTimeRequests.innerHTML = `<strong>${totalRequestsText}</strong>`;
    }

    
    {{--function updateChartFromDate() {--}}
    {{--    const date = document.getElementById('specificDate').value; // Lấy ngày từ input--}}
    {{--    const timeData = @json($timeData); // Đảm bảo dữ liệu timeData có sẵn ở đây--}}

    {{--    console.log(date);--}}

    {{--    // Lọc dữ liệu cho ngày cụ thể--}}
    {{--    const filteredData = timeData['Ngày'].filter(item => item.period === date);--}}

    {{--    // Cập nhật biểu đồ với dữ liệu đã lọc--}}
    {{--    if (filteredData.length > 0) {--}}
    {{--        updateChartWithFilteredDataa(filteredData); // Cập nhật biểu đồ với dữ liệu cho ngày hôm nay--}}
    {{--        displayTotalSummary(filteredData);--}}
    {{--    } else {--}}
    {{--        alert('Không có dữ liệu cho ngày này.'); // Thông báo nếu không có dữ liệu--}}
    {{--    }--}}
    {{--}--}}

    {{--function updateChartFromMonth() {--}}
    {{--    const selectedMonth = document.getElementById('specificMonth').value; // Lấy tháng từ input--}}
    {{--    const [year, month] = selectedMonth.split('-'); // Tách năm và tháng--}}

    {{--    const timeData = @json($timeData); // Dữ liệu gốc--}}

    {{--    console.log('Selected Month:', month); // Debugging--}}
    {{--    console.log('Selected Year:', year); // Debugging--}}

    {{--    // Lọc dữ liệu cho tháng được chọn--}}
    {{--    const filteredData = timeData['Tháng'].filter(item => item.period === parseInt(month));--}}

    {{--    console.log('Filtered data:', filteredData); // Debugging--}}

    {{--    if (filteredData.length > 0) {--}}
    {{--        updateTimeReport('Tháng', filteredData); // Cập nhật biểu đồ cho tháng được chọn--}}
    {{--        displayTotalSummary(filteredData);--}}
    {{--    } else {--}}
    {{--        alert(`Không có dữ liệu cho tháng ${month} năm ${year}.`);--}}
    {{--    }--}}
    {{--}--}}



    // Cập nhật biểu đồ với dữ liệu đã lọc
    function updateChartWithFilteredTimeData(data) {
        console.log("Cập nhật biểu đồ với dữ liệu theo thời gian:", data);
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

        displayTotalSummaryByTime(filteredData, 'year');  // If you're dealing with years

    }

    function showInput(type) {
        // Ẩn tất cả các div nhập liệu
        document.getElementById('dateSelectionInput').style.display = 'none';
        document.getElementById('monthSelectionInput').style.display = 'none';
        document.getElementById('yearSelectionInput').style.display = 'none';
        // Hiển thị div tương ứng với button được nhấn
        if (type === 'dateSelection') {
            const dateSelectionDiv = document.getElementById('dateSelectionInput');
            dateSelectionDiv.style.display = dateSelectionDiv.style.display === 'block' ? 'none' : 'block';

            // Lấy ngày hôm nay
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDatee').value = today; // Ngày bắt đầu
            document.getElementById('endDatee').value = today; // Ngày kết thúc
            updateChartFromDateRange(); // Cập nhật biểu đồ với khoảng ngày này
        }else if (type === 'monthSelection') {
            const monthSelectionDiv = document.getElementById('monthSelectionInput');
            monthSelectionDiv.style.display = monthSelectionDiv.style.display === 'block' ? 'none' : 'block';

            // Lấy tháng hiện tại
            const currentMonth = new Date().toISOString().slice(0, 7);
            document.getElementById('startMonth').value = currentMonth; // Gán tháng hiện tại cho tháng bắt đầu
            document.getElementById('endMonth').value = currentMonth; // Gán tháng hiện tại cho tháng kết thúc

            // Cập nhật biểu đồ ngay lập tức
            updateChartFromMonthRange();
        }else if (type === 'yearSelection') {
            const yearSelectionDiv = document.getElementById('yearSelectionInput');
            yearSelectionDiv.style.display = yearSelectionDiv.style.display === 'block' ? 'none' : 'block';

            if (yearSelectionDiv.style.display === 'block') {
                // Gán năm hiện tại cho năm bắt đầu và năm kết thúc
                const currentYear = new Date().getFullYear();
                document.getElementById('startYear').value = currentYear;
                document.getElementById('endYear').value = currentYear;

                // Cập nhật biểu đồ theo khoảng năm hiện tại
                updateChartFromYearRange();
            }
        }
    }


    // Hàm cập nhật biểu đồ cho khoảng ngày
    function updateChartFromDateRange() {
        const startDatee = document.getElementById('startDatee').value;
        const endDatee = document.getElementById('endDatee').value;

        if (!startDatee || !endDatee) {
            alert('Vui lòng nhập đầy đủ ngày bắt đầu và ngày kết thúc.');
            return;
        }

        const timeData = @json($timeData); // Dữ liệu gốc

        // Chuyển đổi ngày thành đối tượng Date
        const start = new Date(startDatee);
        const end = new Date(endDatee);
        end.setDate(end.getDate() + 1); // Bao gồm ngày kết thúc

        // Lọc dữ liệu cho khoảng thời gian
        const filteredData = timeData['Ngày'].filter(item => {
            const itemDate = new Date(item.period);
            return itemDate >= start && itemDate < end;
        });

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng ngày', filteredData);
            displayTotalSummaryByTime(filteredData);
        } else {
            alert('Không có dữ liệu trong khoảng ngày này.');
        }
    }

    function updateChartFromMonthRange() {
        const startMonthValue = document.getElementById('startMonth').value; // Tháng bắt đầu
        const endMonthValue = document.getElementById('endMonth').value; // Tháng kết thúc

        if (!startMonthValue || !endMonthValue) {
            alert('Vui lòng chọn cả tháng bắt đầu và tháng kết thúc.');
            return;
        }

        const [startYear, startMonth] = startMonthValue.split('-').map(Number);
        const [endYear, endMonth] = endMonthValue.split('-').map(Number);

        const timeData = @json($timeData); // Dữ liệu gốc
        console.log('Filtering data from:', startMonthValue, 'to:', endMonthValue);

        // Lọc dữ liệu trong khoảng tháng
        const filteredData = timeData['Tháng'].filter(item => {
            const itemMonth = item.period; // Tháng từ dữ liệu
            const itemYear = parseInt(startYear); // Giả định năm dựa trên input tháng

            // Kiểm tra xem item nằm trong khoảng tháng
            const isInStartRange = (itemYear > parseInt(startYear) || (itemYear === parseInt(startYear) && itemMonth >= parseInt(startMonth)));
            const isInEndRange = (itemYear < parseInt(endYear) || (itemYear === parseInt(endYear) && itemMonth <= parseInt(endMonth)));

            return isInStartRange && isInEndRange;
        });


        console.log('Filtered Data:', filteredData); // Log dữ liệu đã lọc

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng tháng', filteredData);
            displayTotalSummaryByTime(filteredData);
        } else {
            alert(`Không có dữ liệu cho khoảng thời gian từ tháng ${startMonth} năm ${startYear} đến tháng ${endMonth} năm ${endYear}.`);
        }
    }

    function updateChartFromYearRange() {
        const startYearValue = document.getElementById('startYear').value;
        const endYearValue = document.getElementById('endYear').value;

        if (!startYearValue || !endYearValue) {
            alert('Vui lòng chọn cả năm bắt đầu và năm kết thúc.');
            return;
        }

        const startYear = parseInt(startYearValue);
        const endYear = parseInt(endYearValue);

        if (startYear > endYear) {
            alert('Năm bắt đầu phải nhỏ hơn hoặc bằng năm kết thúc.');
            return;
        }

        const timeData = @json($timeData); // Dữ liệu gốc

        // Lọc dữ liệu theo khoảng năm
        const filteredData = timeData['Năm'].filter(item => {
            const itemYear = parseInt(item.period); // Chuyển thành số
            console.log(`Checking year ${itemYear} with range ${startYearValue} - ${endYearValue}`);
            return itemYear >= parseInt(startYearValue) && itemYear <= parseInt(endYearValue);
        });

        if (filteredData.length > 0) {
            updateTimeReport('Khoảng năm', filteredData);
            displayTotalSummaryByTime(filteredData);
        } else {
            alert(`Không có dữ liệu cho khoảng thời gian từ năm ${startYear} đến năm ${endYear}.`);
        }
    }
</script>

</body>
</html>
