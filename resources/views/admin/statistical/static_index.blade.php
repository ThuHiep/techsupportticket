<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Statistics of Requests by Department</h1>
<canvas id="departmentChart" width="400" height="200"></canvas>

<script>
    const departmentData = @json($departmentData);

    // Check if departmentData has values
    if (Object.keys(departmentData).length === 0) {
        console.error('No data available for the chart.');
    }

    // Extract labels and data for each status
    const labels = Object.keys(departmentData);
    const processingData = labels.map(department => (departmentData[department]["Đang xử lý"] || 0));
    const notProcessedData = labels.map(department => (departmentData[department]["Chưa xử lý"] || 0));
    const completedData = labels.map(department => (departmentData[department]["Hoàn thành"] || 0));
    const canceledData = labels.map(department => (departmentData[department]["Đã hủy"] || 0));

    const ctx = document.getElementById('departmentChart').getContext('2d');
    const departmentChart = new Chart(ctx, {
        type: 'bar', // Change to 'line' if you prefer a line chart
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
</script>
</body>
</html>
