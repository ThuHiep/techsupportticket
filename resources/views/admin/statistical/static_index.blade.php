<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo số yêu cầu theo thời gian</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        #reportContainer {
            max-width: 800px;
            margin: 20px auto;
        }
        #reportrange {
            background: #fff;
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        #totalTimeRequests {
            margin-top: 20px;
            font-size: 18px;
        }
        canvas {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
</head>
<body>

<div class="report-section" id="reportContainer">
    <h3>Báo cáo số yêu cầu theo thời gian</h3>
    <div class="filter-container1">
        <div id="reportrange">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Chọn khoảng thời gian</span> <i class="fa fa-caret-down"></i>
        </div>
    </div>
    <div id="totalTimeRequests"></div>
    <canvas id="requestsChart"></canvas>
</div>

<script type="text/javascript">
    $(function() {
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            displayTotalRequests(start, end); // Hiển thị tổng số yêu cầu
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Năm này': [moment().startOf('year'), moment().endOf('year')],
                'Năm trước': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }, cb);

        cb(start, end);
    });

    function displayTotalRequests(start, end) {
        const timeData = @json($timeData); // Dữ liệu gốc

        // Lọc dữ liệu cho khoảng thời gian
        const filteredData = timeData['Ngày'].filter(item => {
            const itemDate = moment(item.period);
            return itemDate.isBetween(start, end, null, '[]'); // Bao gồm ngày bắt đầu và kết thúc
        });

        const totalRequests = filteredData.reduce((total, item) => {
            return total + (item.total['Đang xử lý'] || 0) + (item.total['Chưa xử lý'] || 0) + (item.total['Hoàn thành'] || 0) + (item.total['Đã hủy'] || 0);
        }, 0);

        document.getElementById('totalTimeRequests').innerHTML = `<strong>Tổng số yêu cầu: ${totalRequests}</strong>`;

        // Vẽ biểu đồ
        drawChart(filteredData);
    }

    function drawChart(data) {
        const labels = data.map(item => item.period);
        const processing = data.map(item => item.total['Đang xử lý'] || 0);
        const pending = data.map(item => item.total['Chưa xử lý'] || 0);
        const completed = data.map(item => item.total['Hoàn thành'] || 0);
        const canceled = data.map(item => item.total['Đã hủy'] || 0);

        const ctx = document.getElementById('requestsChart').getContext('2d');
        const requestsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Đang xử lý',
                        data: processing,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Chưa xử lý',
                        data: pending,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Hoàn thành',
                        data: completed,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Đã hủy',
                        data: canceled,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>

</body>
</html>
