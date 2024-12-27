<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistical Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h1>Statistical Report</h1>

<div>
    <h3>Báo cáo số yêu cầu theo thời gian</h3>
    <label for="specificWeek">Chọn tuần:</label>
    <input type="week" id="specificWeek" onchange="updateChartFromWeek()">
    <div id="weekRangeInput">
        <label for="startWeek">Tuần bắt đầu:</label>
        <input type="week" id="startWeek" onchange="updateChartFromWeekRange()">
        <label for="endWeek">Tuần kết thúc:</label>
        <input type="week" id="endWeek" onchange="updateChartFromWeekRange()">
    </div>
</div>
<canvas id="combinedChart"></canvas>

<script>
    // Sample data structure (replace with your actual data)
    const timeData = @json($timeData);

    let combinedChart;

    // Function to get the current week in "YYYY-WW" format
    function getCurrentWeek() {
        const date = new Date();
        const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
        const days = Math.floor((date - firstDayOfYear) / (24 * 60 * 60 * 1000));
        const weekNumber = Math.ceil((days + firstDayOfYear.getDay() + 1) / 7);
        return `${date.getFullYear()}-${String(weekNumber).padStart(2, '0')}`;
    }

    // Set the current week as the default value for the inputs
    function setCurrentWeek() {
        const currentWeek = getCurrentWeek();
        document.getElementById('specificWeek').value = currentWeek;
        document.getElementById('startWeek').value = currentWeek;
        document.getElementById('endWeek').value = currentWeek;
    }

    function updateChartFromWeek() {
        const selectedWeek = document.getElementById('specificWeek').value;
        console.log('Selected Week:', selectedWeek); // Log the selected week

        if (!selectedWeek) return;

        // Log all time data to check values
        console.log('All Time Data:', timeData);

        const filteredData = timeData['Tuần'].filter(item => item.period === selectedWeek);
        console.log('Filtered Data for Specific Week:', filteredData); // Log the filtered data
        updateChart(filteredData);
    }

    function updateChartFromWeekRange() {
        const startWeek = document.getElementById('startWeek').value;
        const endWeek = document.getElementById('endWeek').value;
        if (!startWeek || !endWeek) return;

        const filteredData = timeData['Tuần'].filter(item => {
            return item.period.localeCompare(startWeek) >= 0 && item.period.localeCompare(endWeek) <= 0;
        });

        console.log('Filtered Data for Week Range:', filteredData); // Log the filtered data for the range
        updateChart(filteredData);
    }

    function updateChart(filteredData) {
        const labels = filteredData.map(item => item.period);
        const datasets = [{
            label: 'Đang xử lý',
            data: filteredData.map(item => item.totals["Đang xử lý"]),
            backgroundColor: 'rgba(75, 192, 192, 0.5)'
        }, {
            label: 'Chưa xử lý',
            data: filteredData.map(item => item.totals["Chưa xử lý"]),
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }, {
            label: 'Hoàn thành',
            data: filteredData.map(item => item.totals["Hoàn thành"]),
            backgroundColor: 'rgba(153, 102, 255, 0.5)'
        }, {
            label: 'Đã hủy',
            data: filteredData.map(item => item.totals["Đã hủy"]),
            backgroundColor: 'rgba(255, 99, 132, 0.5)'
        }];

        console.log('Chart Labels:', labels); // Log the labels
        console.log('Chart Datasets:', datasets); // Log the datasets

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

    // Set the current week on page load
    window.onload = setCurrentWeek;

</script>

</body>
</html>
