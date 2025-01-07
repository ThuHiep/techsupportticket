<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none; /* Mặc định ẩn */
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        border: 8px solid #f3f3f3; /* Màu nền */
        border-top: 8px solid #3498db; /* Màu xoay */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<body class="pace-done body-small">
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>
<div class="pace  pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="background-color: #fff">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" src="admin/img/logosweetsoft.png" style="width:170px; margin-top: -10px; margin-bottom: 20px">
                        <img alt="image" class="img-circle" src="{{$logged_user->profile_image ? asset('admin/img/employee/' . $logged_user->profile_image) : asset('admin/img/employee/default.png') }}" style="margin-left:50px; width: 60px;height: 60px;">
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false">
                        <span class="clear text-align: center;">
                            <span class="block m-t-xs">
                                <strong class="font-bold" style="color: #2e2626; display: inline-block; text-align: center; width: 100%;">{{$logged_user->full_name}} <b class="caret ms-2" style="color: #272020"></b></strong>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ route('employee.editProfile', $logged_user->employee_id) }}" class="profile-link">Hồ sơ</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="img-circle" src="admin/img/logoss.png" style="width:30px; height: 30px">
                </div>
            </li>
            {{-- <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}"><i class="fa fa-th-large"></i>
                    <span>Trang quản trị</span>
                </a>
            </li> --}}
            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}"><i class="fa-solid fa-chart-line"></i>
                    <span class="nav-label"> Trang quản trị</span>
                </a>
            </li>
            @if (auth()->user()->role_id == 1)
                <li class="{{ Request::is('permission*') ? 'active' : '' }}">
                    <a href="{{ route('permission.index') }}"><i class="fas fa-address-book"></i>
                        <span class="nav-label">Tài khoản</span>
                    </a>
                </li>
            @endif
            <li class="{{ Request::is('customer*') ? 'active' : '' }}">
                <a href="{{ route('customer.index') }}"><i class="fa-solid fa-users"></i>
                    <span class="nav-label">Khách hàng</span>
                </a>
            </li>
            <li class="{{ Request::is('department*') ? 'active' : '' }}">
                <a href="{{ route('department.index') }}"><i class="fa-solid fa-clipboard"></i>
                    <span class="nav-label">Phòng ban</span>
                </a>
            </li>
            <li class="{{ Request::is('requesttype*') ? 'active' : '' }}">
                <a href="{{ route('requesttype.index') }}"><i class="fa-solid fa-cogs"></i>
                    <span class="nav-label">Loại yêu cầu</span>
                </a>
            </li>
            <li class="{{ Request::is('request*') && !Request::is('requesttype*') ? 'active' : '' }}">
                <a href="{{ route('request.index') }}">
                    <i class="fa-solid fa-tools"></i>
                    <span class="nav-label">Yêu cầu</span>
                </a>
            </li>

            <li class="{{ Request::is('faq*') ? 'active' : '' }}">
                <a href="{{ route('faq.index') }}"><i class="fa-solid fa-circle-question"></i>

                    <span class="nav-label">Câu hỏi</span>
                </a>
            </li>
            <li class="{{ Request::is('articles*') ? 'active' : '' }}">
                <a href="{{ route('articles.index') }}"><i class="fa-solid fa-lightbulb"></i>

                    <span class="nav-label">Hướng dẫn</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingOverlay = document.getElementById('loading-overlay');

        // Hàm hiển thị overlay loading
        function showLoading() {
            loadingOverlay.style.display = 'flex'; // Hiển thị overlay
        }

        // Ngăn loader xuất hiện khi nhấn vào tên đầy đủ
        const fullNameElement = document.querySelector('.profile-element .block.m-t-xs');

        if (fullNameElement) {
            fullNameElement.addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành động mặc định
                e.stopPropagation(); // Ngăn chặn sự kiện lan truyền
                const dropdownMenu = fullNameElement.closest('.profile-element').querySelector('.dropdown-menu');
                dropdownMenu.classList.toggle('show'); // Hiển thị hoặc ẩn menu dropdown
            });
        }

        // Ngăn loader xuất hiện khi nhấn vào liên kết "Hồ sơ"
        const profileLink = document.querySelector('.profile-link');

        if (profileLink) {
            profileLink.addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành động mặc định
                window.location.href = profileLink.href; // Chuyển hướng đến trang hồ sơ
            });
        }

        // Thêm sự kiện click cho tất cả các mục trong menu từ "Trang quản trị" trở xuống
        const menuItems = document.querySelectorAll('#side-menu a');

        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                showLoading(); // Hiển thị overlay loading khi nhấn vào menu
            });
        });
    });
</script>
</body>
