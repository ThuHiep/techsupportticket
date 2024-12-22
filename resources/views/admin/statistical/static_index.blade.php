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
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }
        #statistics {
            margin-top: 20px;
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
        #departmentFilter {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h1>Statistics of Requests by Department</h1>

<!-- Department Filter Dropdown -->
<select id="departmentFilter">
    <option value="all">All Departments</option>
    @foreach ($departmentData as $department => $data)
        <option value="{{ $department }}">{{ $department }}</option>
    @endforeach
</select>

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
    const labels = Object.keys(departmentData);

    // Function to populate chart data
    function getChartData(selectedDepartment) {
        if (selectedDepartment === 'all') {
            return labels.map(department => ({
                department,
                processing: departmentData[department]["Đang xử lý"] || 0,
                notProcessed: departmentData[department]["Chưa xử lý"] || 0,
                completed: departmentData[department]["Hoàn thành"] || 0,
                canceled: departmentData[department]["Đã hủy"] || 0
            }));
        } else {
            return [{
                department: selectedDepartment,
                processing: departmentData[selectedDepartment]["Đang xử lý"] || 0,
                notProcessed: departmentData[selectedDepartment]["Chưa xử lý"] || 0,
                completed: departmentData[selectedDepartment]["Hoàn thành"] || 0,
                canceled: departmentData[selectedDepartment]["Đã hủy"] || 0
            }];
        }
    }

    let departmentChart; // Declare chart variable

    function updateChart(selectedDepartment) {
        const chartData = getChartData(selectedDepartment);
        const processingData = chartData.map(data => data.processing);
        const notProcessedData = chartData.map(data => data.notProcessed);
        const completedData = chartData.map(data => data.completed);
        const canceledData = chartData.map(data => data.canceled);

        const ctx = document.getElementById('departmentChart').getContext('2d');

        // Clear existing chart if it exists
        if (departmentChart) {
            departmentChart.destroy();
        }

        // Create a new chart
        departmentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: selectedDepartment === 'all' ? labels : [selectedDepartment],
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
    }

    // Initial chart display
    updateChart('all');

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

    // Event listener for department filter
    document.getElementById('departmentFilter').addEventListener('change', function() {
        updateChart(this.value);
    });
</script>
</body>
</html>
