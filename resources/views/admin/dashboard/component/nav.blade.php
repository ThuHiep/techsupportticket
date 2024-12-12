<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0; ">
        <div class="navbar-header">
            <button class="navbar-minimalize minimalize-styl-2 btn btn-warning" type="button">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message" style="color: #FFFFFF; font-weight: bold">Chào mừng đến trang quản trị viên.</span>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info "  data-toggle="dropdown" href="#">
                    <i class="fa fa-bell"></i>  <span class="label label-warning" id="userCountt">0</span>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <a href="{{ route('customer.pending') }}">
                            <div >
                                <i class="fa fa-envelope fa-fw"></i> Có <span id="customerCount">0</span> khách hàng chờ duyệt
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="profile.html">
                            <div>
                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers

                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('auth.login') }}">
                    <i class="fa fa-sign-out"></i> Đăng xuất
                </a>
            </li>

        </ul>

    </nav>
</div>
<script>
    function updateCounts(date = null) {
        // Cập nhật số lượng người dùng
        fetch('{{ route("guest.user.list") }}')
            .then(response => response.json())
            .then(data => {
                const userCountt = data.length; // Giả sử dữ liệu trả về là mảng người dùng
                document.getElementById('userCountt').textContent = userCountt;
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng người dùng:', error));

        // Cập nhật số lượng khách hàng theo ngày
        const url = date ? `{{ route('admin.customer.list') }}?date=${date}` : '{{ route("admin.customer.list") }}';
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const customerCount = data.length; // Giả sử dữ liệu trả về là mảng khách hàng
                document.getElementById('customerCount').textContent = customerCount;
            })
            .catch(error => console.error('Lỗi khi cập nhật số lượng khách hàng:', error));
    }

    // Gọi hàm khi trang được tải
    document.addEventListener('DOMContentLoaded', () => {
        updateCounts(); // Cập nhật ngay khi tải trang

        document.getElementById('updateButton').addEventListener('click', () => {
            const selectedDate = document.getElementById('datePicker').value;
            updateCounts(selectedDate); // Cập nhật số lượng khách hàng theo ngày
        });
    });
</script>
