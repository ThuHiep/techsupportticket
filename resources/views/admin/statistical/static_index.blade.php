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
            display: flex;
            justify-content: space-between;
        }
        .chart-container {
            width: 60%;
            height: 400px;
            font-size: 14px;
        }
        .data-container {
            width: 35%;
            margin-left: 20px;
            font-size: 14px;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        .filter-container button {
            margin-right: 10px;
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .filter-container button:hover {
            background-color: #0056b3;
        }
        .active {
            background-color: orange;
            border-color: orange;
        }
    </style>
</head>
<body>
<div class="container">
    <div>
        <h1>Báo cáo thống kê theo loại yêu cầu</h1>
        <div class="filter-container">
            <button id="btnToday" onclick="filterBy('today')">Hôm nay</button>
            <button id="btnMonthly" onclick="filterBy('monthly')">Tháng</button>
            <button id="btnYearly" onclick="filterBy('yearly')">Năm này</button>
        </div>
        <div class="chart-container">
            <canvas id="requestTypeChart"></canvas>
        </div>
    </div>
    <div class="data-container" id="dataDisplay">
        <h2>Dữ liệu cụ thể</h2>
        <ul id="dataList">
            <!-- Specific data will be populated here -->
        </ul>
    </div>
</div>

<script>
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
                label: 'Số yêu cầu theo loại',
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

    async function filterBy(period) {
        let filteredData = {};
        try {
            const response = await fetch(`/api/get-request-data?period=${period}`);
            filteredData = await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return;
        }

        requestTypeChart.data.labels = Object.keys(filteredData);
        requestTypeChart.data.datasets[0].data = Object.values(filteredData);
        requestTypeChart.update();

        updateButtonStyles(period);
        displaySpecificData(filteredData);
    }

    function updateButtonStyles(activePeriod) {
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
            listItem.textContent = `${key}: ${value}`;
            dataList.appendChild(listItem);
        }
    }
</script>
</body>
</html>
