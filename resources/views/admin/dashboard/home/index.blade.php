
<div class="wrapper wrapper-content">
    <div class="row">
        <!-- Khách hàng -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">Hằng ngày</span>
                    <h5>Khách hàng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalCustomersToday) }}</h1>
                    <div class="stat-percent font-bold" style="color: {{ $customerPercentageChange > 0 ? '#28a745' : ($customerPercentageChange < 0 ? '#dc3545' : '#6c757d') }};">
                        {{ is_numeric($customerPercentageChange) ? number_format($customerPercentageChange, 0) . '%' : $customerPercentageChange }}
                        <i class="fa {{ $customerPercentageChange > 0 ? 'fa-level-up' : ($customerPercentageChange < 0 ? 'fa-level-down' : 'fa-minus') }}"></i>
                    </div>
                    <small>Tổng khách hàng hôm nay</small>
                </div>
            </div>
        </div>

        <!-- Yêu cầu -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">Hằng ngày</span>
                    <h5>Yêu cầu</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalRequestsToday) }}</h1>
                    <div class="stat-percent font-bold" style="color: {{ $requestPercentageChange > 0 ? '#28a745' : ($requestPercentageChange < 0 ? '#dc3545' : '#6c757d') }};">
                        {{ is_numeric($requestPercentageChange) ? number_format($requestPercentageChange, 0) . '%' : $requestPercentageChange }}
                        <i class="fa {{ $requestPercentageChange > 0 ? 'fa-level-up' : ($requestPercentageChange < 0 ? 'fa-level-down' : 'fa-minus') }}"></i>
                    </div>                                                                
                    <small>Tổng yêu cầu hôm nay</small>
                </div>

            </div>
        </div>

        <!-- Bài viết -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-warning pull-right">Hằng ngày</span>
                    <h5>Bài viết</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalFaqsToday) }}</h1>
                    <div class="stat-percent font-bold" style="color: {{ $faqPercentageChange > 0 ? '#28a745' : ($faqPercentageChange < 0 ? '#dc3545' : '#6c757d') }};">
                        {{ is_numeric($faqPercentageChange) ? number_format($faqPercentageChange, 0) . '%' : $faqPercentageChange }}
                        <i class="fa {{ $faqPercentageChange > 0 ? 'fa-level-up' : ($faqPercentageChange < 0 ? 'fa-level-down' : 'fa-minus') }}"></i>
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
          {{-- <span class="label label-warning pull-right" style="font-size: 12px">Hằng ngày</span> --}}
            <h5>Trạng thái yêu cầu</h5>
        </div>
        <div class="ibox-content" style="height:350px; "> <!-- Thêm margin-top để biểu đồ dịch xuống -->
            <canvas id="requestStatusPieChart" style="width: 100%; height: 500px; "></canvas>
        </div>
    </div>

        <!-- Biểu đồ yêu cầu hỗ trợ -->
        <div class="col-lg-7">
            <div class="ibox-title">
                <span class="label label-warning pull-right" style="font-size: 12px">Hằng tuần</span>
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
    <script src="admin/js/jquery-3.1.1.min.js"></script>
    <script src="admin/js/plugins/flot/jquery.flot.js"></script>
    <script src="admin/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="admin/js/plugins/chartJs/Chart.min.js"></script>

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
                        requestStatusCounts.completed,  
                        requestStatusCounts.cancelled
                    ],
                    backgroundColor: ["#F2636B", "#FF9700", "#1AB394", "#A6A8AA"],  // Màu sắc cho mỗi trạng thái
                    hoverBackgroundColor: ["#F2636B", "#FF9700", "#1AB394", "#A6A8AA"]
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
            // Chuyển đổi dữ liệu requestData từ PHP thành định dạng phù hợp cho biểu đồ
            var requestData = {!! json_encode($requestData) !!};
            var data = [];
            var ticks = [];
        
            // Làm tròn dữ liệu trước khi đưa vào biểu đồ
            for (var i = 0; i < requestData.length; i++) {
                var roundedTotal = parseFloat(requestData[i].total).toFixed(1); // Làm tròn số đến 1 chữ số thập phân
                data.push([i, roundedTotal]);
                ticks.push([i, requestData[i].day]);
            }
        
            const chartData = [
                { label: "Yêu cầu", data: data }
            ];
        
            // Cấu hình biểu đồ
            const options = {
                xaxis: {
                    ticks: ticks,
                    mode: "categories", // Hiển thị trục x theo dạng category (ngày trong tuần)
                    tickLength: 0, // Giúp hiển thị nhãn dễ dàng hơn
                },
                yaxis: {
                    min: 0,
                    tickFormatter: function (val) {
                        return parseFloat(val).toFixed(1); // Định dạng giá trị y với 1 chữ số thập phân
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
            $.plot($("#flot-dashboard-chart"), chartData, options);
        });
    </script>
    
    
    

</div>
