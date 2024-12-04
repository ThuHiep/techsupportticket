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
                    {{-- <h1 class="no-margins">{{ number_format($totalFaqsToday) }}</h1>
                    <div class="stat-percent font-bold {{ $faqPercentageChange === '100%+' || $faqPercentageChange > 0 ? 'text-success' : 'text-danger' }}">
                        {{ is_numeric($faqPercentageChange) ? number_format($faqPercentageChange, 2) . '%' : $faqPercentageChange }}
                        <i class="fa {{ $faqPercentageChange === '100%+' || $faqPercentageChange > 0 ? 'fa-level-up' : 'fa-level-down' }}"></i>
                    </div> --}}
                    <small>Tổng bài viết hôm nay</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần tiếp theo -->
    <div class="row">
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Phòng ban</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Dữ liệu</th>
                            <th>Phòng ban</th>
                            <th>Yêu cầu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td><span class="pie" style="display: none;">0.52,1.041</span><svg class="peity" height="16" width="16"></path></svg></td>
                            <td>Samantha</td>
                            <td class="text-navy"> <i class="fa fa-level-up"></i> 40% </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><span class="pie" style="display: none;">226,134</span><svg class="peity" height="16" width="16"></path></svg></td>
                            <td>Jacob</td>
                            <td class="text-warning"> <i class="fa fa-level-down"></i> -20% </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><span class="pie" style="display: none;">0.52/1.561</span><svg class="peity" height="16" width="16"></svg></td>
                            <td>Damien</td>
                            <td class="text-navy"> <i class="fa fa-level-up"></i> 26% </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>

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
                        [0, "Monday"],
                        [1, "Tuesday"],
                        [2, "Wednesday"],
                        [3, "Thursday"],
                        [4, "Friday"],
                        [5, "Saturday"],
                        [6, "Sunday"]
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
