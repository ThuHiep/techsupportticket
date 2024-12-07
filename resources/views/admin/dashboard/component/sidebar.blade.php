
<nav class="navbar-default navbar-static-side" role="navigation" style="">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="background-color: #D4D3D2" > <!--Huy đã đổi màu-->
                <div class="dropdown profile-element " style="background-color: #D4D3D2";> <!--Huy đã xóa class "open" trong div này, đổi màu -->
                    <span>
                    <img alt="image"  src="admin/img/logosweetsoft.png"
                    style="width:170px; margin-top: -10px; margin-bottom: 20px">
                        <img alt="image" class="img-circle" src="admin/img/profile_small.jpg">
                         </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false"> <!--Huy đã cho aria-expanded="false" để ngăn list tự sổ xuống -->
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold" style="color: #2e2626">David Williams</strong>
                                 <b class="caret ms-2" style="color: #272020"></b><!--Huy đã đổi màu tên và icon tại đây-->
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Hồ sơ</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.login') }}">Đăng xuất</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="img-circle" src="admin/img/logoss.png" style="width:30px; height: 30px">
                </div>
            </li>
            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}"><i class="fa fa-th-large"></i>
                    <span >Trang quản trị</span>
                </a>
            </li>
            <li class="{{ Request::is('account*') ? 'active' : '' }}">
                <a href="index.html"><i class="fas fa-id-card"></i>
                    <span class="nav-label">Tài khoản</span>
                </a>
            </li>
            <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
                <a href="index.html"><i class="fas fa-shield-alt"></i>
                    <span class="nav-label">Phân quyền</span>
                </a>
            </li>
            <li class="{{ Request::is('staff*') ? 'active' : '' }}">
                <a href="index.html"><i class="fas fa-address-book"></i>
                    <span class="nav-label">Nhân viên</span>
                </a>
            </li>

            <li class="{{ Request::is('customer*') ? 'active' : '' }}">
                <a href="{{ route('customer.index') }}"><i class="fa-solid fa-users"></i>
                    <span class="nav-label">Khách hàng</span>
                </a>
            </li>
            <li class="{{ Request::is('department*') ? 'active' : '' }}">
                <a href="index.html"><i class="fa-solid fa-clipboard"></i>
                    <span class="nav-label">Phòng ban</span>
                </a>
            </li>
            <li class="{{ Request::is('request*') ? 'active' : '' }}">
                <a href="index.html"><i class="fa-solid fa-tools"></i>
                    <span class="nav-label">Yêu cầu</span>
                </a>
            </li>
            <li class="{{ Request::is('statistics*') ? 'active' : '' }}">
                <a href="{{ route('statistical.index') }}"><i class="fa-solid fa-chart-line"></i>
                    <span class="nav-label">Thống kê</span>
                </a>
            </li>
            <li class="{{ Request::is('news*') ? 'active' : '' }}">
                <a href="index.html"><i class="fa-solid fa-newspaper"></i>
                    <span class="nav-label">Tin tức</span>
                </a>
            </li>
        </ul>

    </div>
</nav>
