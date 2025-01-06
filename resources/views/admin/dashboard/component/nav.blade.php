
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
        <div class="navbar-header">
            <button class="navbar-minimalize minimalize-styl-2 btn btn-warning" type="button">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            @include('admin.dashboard.component.notification')
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="dropdownMenu">
                    <i class="fa fa-bell"></i>
                   <span class="label label-warning" id="userCountt">0</span>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <!-- Hiển thị thông báo yêu cầu chưa xử lý -->
                    <li id="requestNotification" style="display: none;">

                        <a href="{{ route('request.index', ['status_search' => 'Chưa xử lý', 'search_field' => 'request_date', 'request_date_search' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-tasks fa-fw"></i> Có <span id="requestCount">0</span> yêu cầu chưa xử lý
                            </div>
                        </a>
                    </li>
                    <li class="divider" id="requestDivider" style="display: none;"></li>
                
                    <!-- Hiển thị thông báo tài khoản chờ duyệt -->
                    <li id="customerNotification" style="display: none;">
                        <a href="{{ route('customer.pending', ['date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-envelope fa-fw"></i> Có <span id="customerCount">0</span> khách hàng chờ duyệt
                            </div>
                        </a>
                    </li>
                    <li class="divider" id="customerDivider" style="display: none;"></li>
                
                    <!-- Hiển thị thông báo bài viết chưa phản hồi -->
                    <li id="faqNotification" style="display: none;">
                        <a href="{{ route('faq.index', ['status' => 'Chưa phản hồi', 'date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-question-circle fa-fw"></i> Có <span id="unansweredCount">0</span> câu hỏi chưa phản hồi
                            </div>
                        </a>
                    </li>
                </ul>
                
                
            </li>
            
            {{-- <li><a href="{{route('logout')}}">Đăng xuất</a></li> --}}
            <li>
                <a href="#" onclick="confirmLogout(event)">Đăng xuất</a>
            </li>
            
        </ul>
    </nav>
</div>
<script>
    function confirmLogout(event) {
        // Ngăn chặn hành động mặc định khi nhấn vào liên kết
        event.preventDefault();

        // Hiển thị hộp thoại xác nhận
        const confirmation = confirm("Bạn có chắc muốn đăng xuất không?");
        
        // Nếu người dùng xác nhận, chuyển hướng tới trang đăng xuất
        if (confirmation) {
            window.location.href = "{{ route('logout') }}";
        }
    }
</script>

<script>
    // Định nghĩa hàm toggleNotification
    function toggleNotification(notificationId, dividerId, count) {
        const notification = document.getElementById(notificationId);
        const divider = dividerId ? document.getElementById(dividerId) : null;

        if (count > 0) {
            notification.style.display = 'block';
            if (divider) divider.style.display = 'block';
        } else {
            notification.style.display = 'none';
            if (divider) divider.style.display = 'none';
        }
    }

    // Hàm updateCounts
    function updateCounts(date = null) {
        let customerCount = 0;
        let unansweredCount = 0;
        let requestCount = 0;

        const today = date || new Date().toISOString().split('T')[0];

        // Cập nhật số lượng khách hàng chờ duyệt
        const customerUrl = `{{ route('admin.customer.list') }}?date=${today}`;
        fetch(customerUrl)
            .then(response => response.json())
            .then(data => {
                customerCount = data.length || 0; // Dữ liệu giả định là mảng khách hàng
                document.getElementById('customerCount').textContent = customerCount;

                // Ẩn/Hiện thông báo khách hàng
                toggleNotification('customerNotification', 'customerDivider', customerCount);

                // Cập nhật tổng số trên chuông
                updateBellCount(customerCount, unansweredCount, requestCount);
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng khách hàng:', error));

        // Cập nhật số lượng bài viết chưa phản hồi
        const unansweredUrl = `{{ route('faq.unansweredByDate') }}?date=${today}`;
        fetch(unansweredUrl)
            .then(response => response.json())
            .then(data => {
                unansweredCount = data.count || 0; // Dữ liệu giả định chứa 'count'
                document.getElementById('unansweredCount').textContent = unansweredCount;

                // Ẩn/Hiện thông báo bài viết
                toggleNotification('faqNotification', null, unansweredCount);

                // Cập nhật tổng số trên chuông
                updateBellCount(customerCount, unansweredCount, requestCount);
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng bài viết chưa phản hồi:', error));

        // Cập nhật số lượng yêu cầu chưa xử lý
        const requestUrl = `{{ route('request.pendingByDate') }}?date=${today}`;
        fetch(requestUrl)
            .then(response => response.json())
            .then(data => {
                requestCount = data.count || 0; // Dữ liệu giả định chứa 'count'
                document.getElementById('requestCount').textContent = requestCount;

                // Ẩn/Hiện thông báo yêu cầu
                toggleNotification('requestNotification', 'requestDivider', requestCount);

                // Cập nhật tổng số trên chuông
                updateBellCount(customerCount, unansweredCount, requestCount);
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng yêu cầu chưa xử lý:', error));
    }

    // Hàm tính tổng và cập nhật số lượng trên chuông
    function updateBellCount(customerCount, unansweredCount, requestCount) {
        const totalCount = customerCount + unansweredCount + requestCount;
        document.getElementById('userCountt').textContent = totalCount;
    }

    // Gọi hàm khi trang được tải
    document.addEventListener('DOMContentLoaded', () => {
        updateCounts();

        const datePicker = document.getElementById('datePicker');
        if (datePicker) {
            datePicker.addEventListener('change', () => {
                const selectedDate = datePicker.value;
                updateCounts(selectedDate);
            });
        }
    });
</script>

