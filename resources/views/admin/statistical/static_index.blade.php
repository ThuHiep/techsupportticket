<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            margin: 20px;
        }
        #statistics {
            margin-left: 20px;
        }
        table {
            border-collapse: collapse;
            width: 300px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Statistics of Requests by Department</h1>
<canvas id="departmentChart" width="400" height="200"></canvas>
<div id="statistics">
    <h2>Request Counts</h2>
    <table>
        <thead>
        <tr>
            <th>Phòng Ban</th>
            <th>Đang xử lý</th>
            <th>Chưa xử lý</th>
            <th>Hoàn thành</th>
            <th>Đã hủy</th>
        </tr>
        </thead>
        <tbody id="statisticsData"></tbody>
    </table>
</div>

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

    // Populate the statistics table
    const statisticsData = document.getElementById('statisticsData');

    labels.forEach(department => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${department}</td>
            <td>${departmentData[department]["Đang xử lý"] || 0}</td>
            <td>${departmentData[department]["Chưa xử lý"] || 0}</td>
            <td>${departmentData[department]["Hoàn thành"] || 0}</td>
            <td>${departmentData[department]["Đã hủy"] || 0}</td>
        `;
        statisticsData.appendChild(row);
    });
</script>
</body>
</html>
