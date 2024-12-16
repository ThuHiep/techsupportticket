<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <title>Báo cáo thống kê</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .chart-container {
            width: 100%;
            height: 400px; /* Set desired height */
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Báo cáo thống kê loại yêu cầu</h1>
    <div class="chart-container">
        <canvas id="requestTypeChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('requestTypeChart').getContext('2d');
    const datasets = @json($response).map(item => ({
        label: item.request_type_name,
        data: item.counts,
        borderColor: getRandomColor(),
        backgroundColor: getRandomColor(0.2),
        fill: true, // Tô màu dưới đường
    }));

    const requestTypeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [...Array(31).keys()].map(i => i + 1), // Ngày từ 1 đến 31
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ngày'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số yêu cầu'
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            elements: {
                line: {
                    tension: 0.4 // Để tạo độ uốn lượn cho đường
                },
                point: {
                    radius: 5 // Kích thước của các điểm
                }
            }
        }
    });

    function getRandomColor(alpha = 1) {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color; // Chỉ trả về màu hex
    }
</script>
</body>
</html>
