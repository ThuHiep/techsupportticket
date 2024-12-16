<nav class="navbar-default navbar-static-side" role="navigation" style="">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="background-color: #fff"> <!--Huy đã đổi màu-->
                <div class="dropdown profile-element " ;> <!--Huy đã xóa class "open" trong div này, đổi màu -->
                    <span>
                        <img alt="image" src="admin/img/logosweetsoft.png"
                            style="width:170px; margin-top: -10px; margin-bottom: 20px">
                        <img alt="image" class="img-circle" src="{{$logged_user->profile_image ? asset('admin/img/employee/' .  $logged_user->profile_image) : asset('admin/img/employee/default.png') }}" style="margin-left:50px; width: 60px;height: 60px;">
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false"> <!--Huy đã cho aria-expanded="false" để ngăn list tự sổ xuống -->
                        <span class="clear text-align: center;">
                            <span class="block m-t-xs">
                                <strong class="font-bold" style="color: #2e2626; display: inline-block; text-align: center; width: 100%;">{{$logged_user->full_name}} <b class="caret ms-2" style="color: #272020"></b></strong>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ route('employee.editProfile', $logged_user->employee_id) }}">Hồ sơ</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="img-circle" src="admin/img/logoss.png" style="width:30px; height: 30px">
                </div>
            </li>
            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}"><i class="fa fa-th-large"></i>
                    <span>Trang quản trị</span>
                </a>
            </li>
            <li class="{{ Request::is('permission*') ? 'active' : '' }}">
                <a href="{{ route('permission.index') }}"><i class="fas fa-address-book"></i>
                    <span class="nav-label">Người dùng</span>
                </a>
            </li>
            <li class="{{ Request::is('customer*') ? 'active' : '' }}">
                <a href="{{ route('customer.index') }}"><i class="fa-solid fa-users"></i>
                    <span class="nav-label">Khách hàng</span>
                </a>
            </li>
            @if ($logged_user->user->role_id == 1)
            <li class="{{ Request::is('department*') ? 'active' : '' }}">
                <a href="department/index"><i class="fa-solid fa-clipboard"></i>
                    <span class="nav-label">Phòng ban</span>
                </a>
            </li>
            @endif
            <li class="{{ Request::is('request*') ? 'active' : '' }}">
                <a href="request/index"><i class="fa-solid fa-tools"></i>
                    <span class="nav-label">Yêu cầu</span>
                </a>
            </li>
            <li class="{{ Request::is('statistical*') ? 'active' : '' }}">
                <a href="{{ route('statistical.index') }}"><i class="fa-solid fa-chart-line"></i>
                    <span class="nav-label">Thống kê</span>
                </a>
            </li>
            <li class="{{ Request::is('faq*') ? 'active' : '' }}">
                <a href="{{ route('faq.index') }}"><i class="fa-solid fa-newspaper"></i>
                    <span class="nav-label">Tin tức</span>
                </a>
            </li>
        </ul>
    </div>
</nav>