
<div class="wrapper wrapper-content">
    <div class="row">
        <!-- Khách hàng -->
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Hằng ngày</span>
                    <h5>Khách hàng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalCustomersToday) }}</h1>
                    <div class="stat-percent font-bold {{ $customerPercentageChange === '100%+' || $customerPercentageChange > 0 ? 'text-success' : 'text-danger' }}">
                        {{ is_numeric($customerPercentageChange) ? number_format($customerPercentageChange, 2) . '%' : $customerPercentageChange }}
                        <i class="fa {{ $customerPercentageChange === '100%+' || $customerPercentageChange > 0 ? 'fa-level-up' : 'fa-level-down' }}"></i>
                    </div>
                    <small>Tổng khách hàng hôm nay</small>
                </div>
            </div>
        </div>

        <!-- Yêu cầu -->
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">Hằng ngày</span>
                    <h5>Yêu cầu</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalRequestsToday) }}</h1>
                    <div class="stat-percent font-bold {{ $requestPercentageChange === '100%+' || $requestPercentageChange > 0 ? 'text-success' : 'text-danger' }}">
                        {{ is_numeric($requestPercentageChange) ? number_format($requestPercentageChange, 2) . '%' : $requestPercentageChange }}
                        <i class="fa {{ $requestPercentageChange === '100%+' || $requestPercentageChange > 0 ? 'fa-level-up' : 'fa-level-down' }}"></i>
                    </div>
                    <small>Tổng yêu cầu hôm nay</small>
                </div>
            </div>
        </div>

        <!-- Người dùng -->
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">Hằng ngày</span>
                    <h5>Người dùng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalUsersToday) }}</h1>
                    <div class="stat-percent font-bold {{ $userPercentageChange === '100%+' || $userPercentageChange > 0 ? 'text-success' : 'text-danger' }}">
                        {{ is_numeric($userPercentageChange) ? number_format($userPercentageChange, 2) . '%' : $userPercentageChange }}
                        <i class="fa {{ $userPercentageChange === '100%+' || $userPercentageChange > 0 ? 'fa-level-up' : 'fa-level-down' }}"></i>
                    </div>
                    <small>Tổng người dùng hôm nay</small>
                </div>
            </div>
        </div>

        <!-- Bài viết -->
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Hằng ngày</span>
                    <h5>Bài viết</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalFaqsToday) }}</h1>
                    <div class="stat-percent font-bold {{ $faqPercentageChange > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $faqPercentageChange }}
                        <i class="fa {{ $faqPercentageChange > 0 ? 'fa-level-up' : 'fa-level-down' }}"></i>
                    </div>

                    <small>Tổng bài viết hôm nay</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần tiếp theo -->
    <div class="row">

        <div class="col-lg-5">
        <div class="ibox-title">
            <h5>Trạng thái yêu cầu</h5>
        </div>
        <div class="ibox-content" style="height:350px; "> <!-- Thêm margin-top để biểu đồ dịch xuống -->
            <canvas id="requestStatusPieChart" style="width: 100%; height: 500px; "></canvas>
        </div>
    </div>

        <!-- Biểu đồ yêu cầu hỗ trợ -->
        <div class="col-lg-7">
            <div class="ibox-title">
                <h5>Yêu cầu hỗ trợ</h5>
            </div>
            <div class="ibox-content" style="height:350px">
                <div class="flot-chart">
                    <div id="flot-dashboard-chart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm script -->
    <script src="backend/js/jquery-3.1.1.min.js"></script>
    <script src="backend/js/plugins/flot/jquery.flot.js"></script>
    <script src="backend/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="backend/js/plugins/chartJs/Chart.min.js"></script>

    <script>
        $(document).ready(function () {
            // Chuyển mảng PHP sang JSON để sử dụng trong JavaScript
            var requestStatusCounts = {!! json_encode($requestStatusCounts) !!};

            // Kiểm tra giá trị của requestStatusCounts
            console.log(requestStatusCounts);

            // Dữ liệu biểu đồ pie
            var data = {
                labels: ["Chưa xử lý", "Đang xử lý", "Hoàn thành", "Đã hủy"],  // Các nhãn cho từng trạng thái
                datasets: [{
                    data: [
                        requestStatusCounts.processing,
                        requestStatusCounts.handled,
                        requestStatusCounts.completed,  // Đảm bảo dữ liệu cho "Hoàn thành" đã được truyền vào đây
                        requestStatusCounts.cancelled
                    ],
                    backgroundColor: ["#FF5733", "#FF9700", "#1AB394", "#A6A8AA"],  // Màu sắc cho mỗi trạng thái
                    hoverBackgroundColor: ["#FF5733", "#FF9700", "#1AB394", "#A6A8AA"]
                }]
            };

            // Cấu hình biểu đồ
            var options = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' yêu cầu';
                            }
                        }
                    }
                }
            };

            // Vẽ biểu đồ pie chart
            var ctx = document.getElementById('requestStatusPieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: options
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Dữ liệu cho biểu đồ: Thống kê yêu cầu trong tuần
            const data = [
                { label: "Yêu cầu", data: [[0, 15], [1, 25], [2, 35], [3, 20], [4, 45], [5, 30], [6, 50]] }
            ];

            // Cấu hình biểu đồ
            const options = {
                xaxis: {
                    ticks: [
                        [0, "Thứ Hai"],
                        [1, "Thứ Ba"],
                        [2, "Thứ Tư"],
                        [3, "Thứ Năm"],
                        [4, "Thứ Sáu"],
                        [5, "Thứ Bảy"],
                        [6, "Chủ Nhật"]
                    ]
                },
                yaxis: {
                    min: 0,
                    max: 50,
                    tickFormatter: function (val) {
                        return val;
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                series: {
                    lines: {
                        show: true,
                        fill: true
                    },
                    points: {
                        show: true
                    }
                },
                legend: {
                    position: "ne"
                }
            };

            // Vẽ biểu đồ với dữ liệu và cấu hình
            $.plot($("#flot-dashboard-chart"), data, options);
        });
    </script>

</div>
