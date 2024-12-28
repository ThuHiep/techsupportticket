<body class="pace-done body-small"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
    <div class="pace-progress-inner"></div>
  </div>
  <div class="pace-activity"></div></div>

<nav class="navbar-default navbar-static-side" role="navigation" style="">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="background-color: #fff"> 
                <div class="dropdown profile-element " ;> 
                    <span>
                        <img alt="image" src="admin/img/logosweetsoft.png"
                            style="width:170px; margin-top: -10px; margin-bottom: 20px">
                        <img alt="image" class="img-circle" src="{{$logged_user->profile_image ? asset('admin/img/employee/' .  $logged_user->profile_image) : asset('admin/img/employee/default.png') }}" style="margin-left:50px; width: 60px;height: 60px;">
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false"> 
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
                    <span class="nav-label">Tài khoản</span>
                </a>
            </li>
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
            <li class="{{ Request::is('request*') ? 'active' : '' }}">
                <a href="{{ route('request.index') }}"><i class="fa-solid fa-tools"></i>
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
            <li class="{{ Request::is('articles*') ? 'active' : '' }}">
                <a href="{{ route('articles.index') }}"><i class="fa-solid fa-lightbulb"></i>

                    <span class="nav-label">Hướng dẫn</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
</body>
