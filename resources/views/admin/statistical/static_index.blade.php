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
        h1, h2 {
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
    </style>
</head>
<body>

<h1>Statistical Report</h1>

<div class="btn-group">
    <button class="btn active" data-label="Ngày">Ngày</button>
    <button class="btn" data-label="Tuần">Tuần</button>
    <button class="btn" data-label="Tháng">Tháng</button>
    <button class="btn" data-label="Năm">Năm</button>
    <button class="btn" data-label="Loại yêu cầu">Loại yêu cầu</button>
    <select id="selectedDepartmentFilter"></select>
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

<div class="chart-container">
    <canvas id="combinedChart"></canvas>
</div>

<script>
    const departments = @json($departments);
    const requestTypes = @json($requestTypes);
    const timeData = @json($timeData);

    const datasets = {
        'Đang xử lý': {
            label: 'Đang xử lý',
            data: [],
            backgroundColor: 'rgba(75, 192, 192, 1)',
        },
        'Chưa xử lý': {
            label: 'Chưa xử lý',
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 1)',
        },
        'Hoàn thành': {
            label: 'Hoàn thành',
            data: [],
            backgroundColor: 'rgba(54, 162, 235, 1)',
        },
        'Đã hủy': {
            label: 'Đã hủy',
            data: [],
            backgroundColor: 'rgba(255, 206, 86, 1)',
        }
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
                x: {
                    title: { display: true, text: 'Thời gian' }
                },
                y: {
                    title: { display: true, text: 'Số yêu cầu' },
                    beginAtZero: true
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

        populateDepartmentOptions(); // Populate department options
        updateChart('Ngày');

        addEventListeners();
    });

    function addEventListeners() {
        const selectedDate = document.getElementById('selectedDate');
        const selectedWeek = document.getElementById('selectedWeek');
        const selectedMonth = document.getElementById('selectedMonth');
        const selectedYear = document.getElementById('selectedYear');
        const selectedDepartmentFilter = document.getElementById('selectedDepartmentFilter');

        selectedDate.addEventListener('change', () => updateChartData('Ngày'));
        selectedWeek.addEventListener('change', () => updateChartData('Tuần'));
        selectedMonth.addEventListener('change', () => updateChartData('Tháng'));
        selectedYear.addEventListener('change', () => updateChartData('Năm'));
        selectedDepartmentFilter.addEventListener('change', () => updateChartData(document.querySelector('.btn.active').getAttribute('data-label')));

        document.querySelectorAll('.btn').forEach(button => {
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
        } else if (timePeriod === 'Phòng ban') {
            populateDepartmentOptions();
        }

        updateChartData(timePeriod);
    }

    function updateChartData(timePeriod) {
        let selectedData;
        const selectedDepartment = document.getElementById('selectedDepartmentFilter').value;

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
        } else if (timePeriod === 'Phòng ban') {
            selectedData = timeData['Phòng ban'].filter(item => item.departmentId == selectedDepartment);
        }
        if (selectedData && selectedData.length > 0) {
            combinedChart.data.labels = [selectedData[0].period]; // Adjust as needed
            Object.keys(datasets).forEach(status => {
                combinedChart.data.datasets.find(dataset => dataset.label === status).data = [selectedData[0].total[status] || 0];
            });
            combinedChart.update();
        } else {
            if (timePeriod === 'Tuần') {
                console.warn('Tuần đã chọn không có dữ liệu, nhưng vẫn hiển thị biểu đồ.');
            } else {
                alert('Không có dữ liệu cho lựa chọn đã chọn.');
            }
        }
    }

    function populateMonthOptions() {
        const monthSelect = document.getElementById('selectedMonth');
        monthSelect.innerHTML = '';
        const months = [
            { value: '1', text: 'Tháng 1' },
            { value: '2', text: 'Tháng 2' },
            { value: '3', text: 'Tháng 3' },
            { value: '4', text: 'Tháng 4' },
            { value: '5', text: 'Tháng 5' },
            { value: '6', text: 'Tháng 6' },
            { value: '7', text: 'Tháng 7' },
            { value: '8', text: 'Tháng 8' },
            { value: '9', text: 'Tháng 9' },
            { value: '10', text: 'Tháng 10' },
            { value: '11', text: 'Tháng 11' },
            { value: '12', text: 'Tháng 12' },
        ];

        months.forEach(month => {
            const option = document.createElement('option');
            option.value = month.value;
            option.textContent = month.text;
            monthSelect.appendChild(option);
        });
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

    function populateDepartmentOptions() {
        const departmentSelect = document.getElementById('selectedDepartmentFilter');
        departmentSelect.innerHTML = '';

        if (departments && departments.length > 0) {
            departments.forEach(department => {
                const option = document.createElement('option');
                option.value = department.department_id;
                option.textContent = department.department_name;
                departmentSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.textContent = 'Không có phòng ban nào';
            departmentSelect.appendChild(option);
        }
    }
</script>

</body>
</html>
