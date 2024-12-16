
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
        <div class="navbar-header">
            <button class="navbar-minimalize minimalize-styl-2 btn btn-warning" type="button">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="dropdownMenu">
                    <i class="fa fa-bell"></i> <span class="label label-warning" id="userCountt">0</span>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <a href="{{ route('customer.pending', ['date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-envelope fa-fw"></i> Có <span id="customerCount">0</span> khách hàng chờ duyệt
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('faq.index', ['status' => 'Chưa phản hồi', 'date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-question-circle fa-fw"></i>
                                Có <span id="unansweredCount">0</span> bài viết chưa phản hồi
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li><a href="{{route('logout')}}">Đăng xuất</a></li>
        </ul>
    </nav>
</div>

<script>
    function updateCounts(date = null) {
        let customerCount = 0;
        let unansweredCount = 0;

        // Nếu không có ngày, sử dụng ngày hôm nay
        const today = date || new Date().toISOString().split('T')[0];

        // Cập nhật số lượng khách hàng chờ duyệt
        const customerUrl = `{{ route('admin.customer.list') }}?date=${today}`;

        fetch(customerUrl)
            .then(response => response.json())
            .then(data => {
                customerCount = data.length; // Giả sử dữ liệu trả về là mảng khách hàng
                document.getElementById('customerCount').textContent = customerCount;
                updateBellCount(customerCount, unansweredCount); // Gọi hàm tính tổng
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng khách hàng:', error));

        // Cập nhật số lượng bài viết chưa phản hồi
        const unansweredUrl = date ?
            `{{ route('faq.unansweredByDate') }}?date=${today}` :
            '{{ route("faq.unansweredByDate") }}';

        fetch(unansweredUrl)
            .then(response => response.json())
            .then(data => {
                unansweredCount = data.count; // Dữ liệu trả về từ API
                document.getElementById('unansweredCount').textContent = unansweredCount;
                updateBellCount(customerCount, unansweredCount); // Gọi hàm tính tổng
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng bài viết chưa phản hồi:', error));
    }

    // Hàm tính tổng và cập nhật số lượng trên chuông
    function updateBellCount(customerCount, unansweredCount) {
        const totalCount = customerCount + unansweredCount;
        document.getElementById('userCountt').textContent = totalCount;
    }

    // Gọi hàm khi trang được tải
    document.addEventListener('DOMContentLoaded', () => {
        updateCounts(); // Cập nhật ngay khi tải trang

        // Nếu có input chọn ngày, thêm sự kiện thay đổi
        const datePicker = document.getElementById('datePicker');
        if (datePicker) {
            datePicker.addEventListener('change', () => {
                const selectedDate = datePicker.value;
                updateCounts(selectedDate); // Cập nhật số lượng theo ngày
            });
        }
    });
</script>
