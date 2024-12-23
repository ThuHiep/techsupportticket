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
    </style>
</head>
<body>

<h1>Statistical Report</h1>

<div class="btn-group">
    <button class="btn active" data-label="Ngày">Ngày</button>
    <button class="btn" data-label="Tuần">Tuần</button>
    <button class="btn" data-label="Tháng">Tháng</button>
    <button class="btn" data-label="Năm">Năm</button>
</div>

<div class="chart-container">
    <canvas id="combinedChart"></canvas>
</div>

<script>
    // Ensure the data is correctly passed from your backend
    const timeData = @json($timeData);
    console.log(timeData); // Debugging line to check the loaded data

    // Prepare datasets for each status
    const datasets = {
        'Đang xử lý': {
            label: 'Đang xử lý',
            data: [],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: true,
            hidden: false
        },
        'Đã xử lý': {
            label: 'Đã xử lý',
            data: [],
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 2,
            fill: true,
            hidden: true
        },
        'Hoàn thành': {
            label: 'Hoàn thành',
            data: [],
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 2,
            fill: true,
            hidden: true
        },
        'Đã hủy': {
            label: 'Đã hủy',
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            fill: true,
            hidden: true
        }
    };

    // Create the chart
    const ctx = document.getElementById('combinedChart').getContext('2d');
    const combinedChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // Will be set dynamically based on the selected time period
            datasets: Object.values(datasets)
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Thời gian'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Số yêu cầu'
                    },
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

    // Function to update the chart based on the selected time period
    function updateChart(timePeriod) {
        if (timeData[timePeriod]) {
            combinedChart.data.labels = timeData[timePeriod].map(item => item.period); // Update labels

            // Update datasets for each status
            Object.keys(datasets).forEach(status => {
                datasets[status].data = timeData[timePeriod].map(item => item.total); // Update data
            });

            combinedChart.update();
        } else {
            console.error(`No data available for time period: ${timePeriod}`);
        }
    }

    // Initialize with default time period
    updateChart('Ngày');

    // Button click event to filter datasets by time period
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', () => {
            const timePeriod = button.getAttribute('data-label');
            document.querySelectorAll('.btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            updateChart(timePeriod);
        });
    });
</script>

</body>
</html>
