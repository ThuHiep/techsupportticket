<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistical Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 40vh;
            width: 80vw;
        }
        .btn-group {
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px;
            margin-right: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            border-radius: 5px;
        }
        .btn.active {
            background-color: #007BFF;
            color: white;
        }
        select {
            margin-left: 10px;
            padding: 5px;
        }
        .dropdown {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1000;
            margin-top: 5px;
            padding: 10px;
        }
        .dropdown div {
            padding: 5px;
            cursor: pointer;
        }
        .dropdown div:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Statistical Report</h1>

<div class="btn-group">
    <button class="btn active" data-label="Ngày">Ngày</button>
    <button class="btn" data-label="Tuần">Tuần</button>
    <button class="btn" data-label="Tháng">Tháng</button>
    <button class="btn" data-label="Năm">Năm</button>
    <button class="btn" id="requestTypeButton">Chọn loại yêu cầu</button>
    <button class="btn" id="departmentButton">Chọn phòng ban</button>
</div>

<div id="date-picker" style="display: none;">
    <input type="date" id="selectedDate" />
</div>

<div id="week-picker" style="display: none;">
    <select id="selectedWeek"></select>
</div>

<div id="month-picker" style="display: none;">
    <select id="selectedMonth"></select>
</div>

<div id="year-picker" style="display: none;">
    <select id="selectedYear"></select>
</div>

<div id="requestTypeDropdown" class="dropdown" style="display: none;">
    <div id="requestTypeOptions"></div>
</div>

<div id="departmentDropdown" class="dropdown" style="display: none;">
    <div id="departmentOptions"></div>
</div>

<div class="chart-container">
    <canvas id="combinedChart"></canvas>
</div>

<script>
    const departments = @json($departments);
    const requestTypes = @json($requestTypes);
    const timeData = @json($timeData);

    const datasets = {
        'Đang xử lý': { label: 'Đang xử lý', data: [], backgroundColor: 'rgba(75, 192, 192, 1)' },
        'Chưa xử lý': { label: 'Chưa xử lý', data: [], backgroundColor: 'rgba(255, 99, 132, 1)' },
        'Hoàn thành': { label: 'Hoàn thành', data: [], backgroundColor: 'rgba(54, 162, 235, 1)' },
        'Đã hủy': { label: 'Đã hủy', data: [], backgroundColor: 'rgba(255, 206, 86, 1)' }
    };

    const ctx = document.getElementById('combinedChart').getContext('2d');
    const combinedChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: Object.values(datasets)
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Thời gian' } },
                y: { title: { display: true, text: 'Số yêu cầu' }, beginAtZero: true }
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

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('selectedDate').value = formattedDate;

        populateWeekOptions();
        setCurrentWeek();
        populateMonthOptions();
        document.getElementById('selectedMonth').value = today.getMonth() + 1;
        populateYearOptions();
        document.getElementById('selectedYear').value = today.getFullYear();

        populateRequestTypeOptions();
        populateDepartmentOptions();
        updateChart('Ngày');

        addEventListeners();
    });

    function addEventListeners() {
        const selectedDate = document.getElementById('selectedDate');
        const selectedWeek = document.getElementById('selectedWeek');
        const selectedMonth = document.getElementById('selectedMonth');
        const selectedYear = document.getElementById('selectedYear');

        selectedDate.addEventListener('change', () => updateChartData('Ngày'));
        selectedWeek.addEventListener('change', () => updateChartData('Tuần'));
        selectedMonth.addEventListener('change', () => updateChartData('Tháng'));
        selectedYear.addEventListener('change', () => updateChartData('Năm'));

        document.querySelectorAll('.btn[data-label]').forEach(button => {
            button.addEventListener('click', () => {
                const timePeriod = button.getAttribute('data-label');
                document.querySelectorAll('.btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                updateChart(timePeriod);
            });
        });
    }

    function setCurrentWeek() {
        const weekSelect = document.getElementById('selectedWeek');
        const currentWeek = getWeekNumber(new Date());
        weekSelect.value = currentWeek;
    }

    function getWeekNumber(d) {
        const firstDayOfYear = new Date(d.getFullYear(), 0, 1);
        const days = Math.floor((d - firstDayOfYear) / (24 * 60 * 60 * 1000));
        return Math.ceil((days + firstDayOfYear.getDay() + 1) / 7);
    }

    function updateChart(timePeriod) {
        document.getElementById('date-picker').style.display = 'none';
        document.getElementById('week-picker').style.display = 'none';
        document.getElementById('month-picker').style.display = 'none';
        document.getElementById('year-picker').style.display = 'none';

        if (timePeriod === 'Ngày') {
            document.getElementById('date-picker').style.display = 'block';
        } else if (timePeriod === 'Tuần') {
            document.getElementById('week-picker').style.display = 'block';
        } else if (timePeriod === 'Tháng') {
            document.getElementById('month-picker').style.display = 'block';
        } else if (timePeriod === 'Năm') {
            document.getElementById('year-picker').style.display = 'block';
        }

        updateChartData(timePeriod);
    }

    function updateChartData(timePeriod) {
        let selectedData = [];
        const selectedDepartment = document.getElementById('departmentButton').textContent;
        const selectedRequestType = document.getElementById('requestTypeButton').textContent;

        if (timePeriod === 'Ngày') {
            const selectedDate = document.getElementById('selectedDate').value;
            selectedData = timeData['Ngày'].filter(item => item.period === selectedDate);
        } else if (timePeriod === 'Tuần') {
            const selectedWeek = document.getElementById('selectedWeek').value;
            selectedData = timeData['Tuần'].filter(item => item.period === selectedWeek);
        } else if (timePeriod === 'Tháng') {
            const selectedMonth = document.getElementById('selectedMonth').value;
            selectedData = timeData['Tháng'].filter(item => item.period == selectedMonth);
        } else if (timePeriod === 'Năm') {
            const selectedYear = document.getElementById('selectedYear').value;
            selectedData = timeData['Năm'].filter(item => item.period == selectedYear);
        }

        if (selectedData.length > 0) {
            combinedChart.data.labels = [selectedData[0].period];
            Object.keys(datasets).forEach(status => {
                combinedChart.data.datasets.find(dataset => dataset.label === status).data = [selectedData[0].total[status] || 0];
            });
            combinedChart.update();
        } else {
            alert('Không có dữ liệu cho lựa chọn đã chọn.');
        }
    }

    function populateMonthOptions() {
        const monthSelect = document.getElementById('selectedMonth');
        monthSelect.innerHTML = '';
        for (let month = 1; month <= 12; month++) {
            const option = document.createElement('option');
            option.value = month;
            option.textContent = `Tháng ${month}`;
            monthSelect.appendChild(option);
        }
    }

    function populateYearOptions() {
        const yearSelect = document.getElementById('selectedYear');
        yearSelect.innerHTML = '';
        const currentYear = new Date().getFullYear();
        for (let year = currentYear - 5; year <= currentYear + 5; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    }

    function populateWeekOptions() {
        const weekSelect = document.getElementById('selectedWeek');
        weekSelect.innerHTML = '';
        for (let week = 1; week <= 52; week++) {
            const option = document.createElement('option');
            option.value = week;
            option.textContent = `Tuần ${week}`;
            weekSelect.appendChild(option);
        }
    }

    document.getElementById('requestTypeButton').addEventListener('click', () => {
        const dropdown = document.getElementById('requestTypeDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        populateRequestTypeOptions();
    });

    document.getElementById('departmentButton').addEventListener('click', () => {
        const dropdown = document.getElementById('departmentDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        populateDepartmentOptions();
    });

    function populateRequestTypeOptions() {
        const optionsContainer = document.getElementById('requestTypeOptions');
        optionsContainer.innerHTML = '';
        requestTypes.forEach(type => {
            const option = document.createElement('div');
            option.textContent = type.request_type_name;
            option.onclick = () => {
                document.getElementById('requestTypeButton').textContent = type.request_type_name;
                updateChartData(document.querySelector('.btn.active').getAttribute('data-label'));
                document.getElementById('requestTypeDropdown').style.display = 'none';
            };
            optionsContainer.appendChild(option);
        });
    }

    // Add a separate function for handling department selection
    function selectDepartment(departmentName) {
        document.getElementById('departmentButton').textContent = departmentName;
        document.getElementById('requestTypeDropdown').style.display = 'none'; // Hide request type dropdown
        updateChartData(document.querySelector('.btn.active').getAttribute('data-label'));
    }

    function populateDepartmentOptions() {
        const optionsContainer = document.getElementById('departmentOptions');
        optionsContainer.innerHTML = '';
        departments.forEach(department => {
            const option = document.createElement('div');
            option.textContent = department.department_name;
            option.onclick = () => {
                selectDepartment(department.department_name);
                document.getElementById('departmentDropdown').style.display = 'none';
            };
            optionsContainer.appendChild(option);
        });
    }

    // Ensure dropdowns close when clicking outside
    document.addEventListener('click', function(event) {
        const requestTypeDropdown = document.getElementById('requestTypeDropdown');
        const departmentDropdown = document.getElementById('departmentDropdown');

        if (!requestTypeDropdown.contains(event.target) && !document.getElementById('requestTypeButton').contains(event.target)) {
            requestTypeDropdown.style.display = 'none';
        }
        if (!departmentDropdown.contains(event.target) && !document.getElementById('departmentButton').contains(event.target)) {
            departmentDropdown.style.display = 'none';
        }
    });
</script>

</body>
</html>
