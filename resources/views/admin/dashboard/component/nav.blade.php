
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
        <div class="navbar-header">
            <button class="navbar-minimalize minimalize-styl-2 btn btn-warning" type="button">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell"></i> <span class="label label-warning" id="userCountt">0</span>
                </a>
                
                <ul class="dropdown-menu dropdown-messages">
                    
                    <li>
                        <a href="{{ route('faq.index', ['status' => 'Chưa phản hồi', 'date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-question-circle fa-fw"></i> Có <span id="unansweredCount">0</span> bài viết chưa phản hồi
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('customer.pending', ['date' => now()->toDateString()]) }}">
                            <div>
                                <i class="fa fa-user fa-fw"></i> Có <span id="pendingAccountsCount">0</span> tài khoản chờ duyệt
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
    let pendingAccountsCount = 0;


    // Cập nhật số lượng bài viết chưa phản hồi
    const unansweredUrl = date ?
        `{{ route('faq.unansweredByDate') }}?date=${date}` :
        '{{ route("faq.unansweredByDate") }}';

    fetch(unansweredUrl)
        .then(response => response.json())
        .then(data => {
            unansweredCount = data.count; // Dữ liệu trả về từ API
            document.getElementById('unansweredCount').textContent = unansweredCount;
            updateBellCount(customerCount, unansweredCount, pendingAccountsCount);
        })
        .catch(error => console.error('Lỗi khi cập nhật số lượng bài viết chưa phản hồi:', error));

    // Cập nhật số lượng tài khoản chờ duyệt theo ngày
    const pendingUrl = date ?
        `{{ route('customer.pending') }}?date=${date}` :
        '{{ route("customer.pending") }}';

    fetch(pendingUrl)
        .then(response => response.json())
        .then(data => {
            pendingAccountsCount = data.count; // Dữ liệu trả về từ API
            document.getElementById('pendingAccountsCount').textContent = pendingAccountsCount;
            updateBellCount(customerCount, unansweredCount, pendingAccountsCount);
        })
        .catch(error => console.error('Lỗi khi cập nhật số lượng tài khoản chờ duyệt:', error));
}

// Hàm tính tổng và cập nhật số lượng trên chuông
function updateBellCount(customerCount, unansweredCount, pendingAccountsCount) {
    const totalCount = customerCount + unansweredCount + pendingAccountsCount;
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