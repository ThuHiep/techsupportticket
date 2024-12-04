<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="padding: 15px; z-index: 1000;">
                <div class="dropdown profile-element"> <span>
                    <img alt="image" src="backend/img/logosweetsoft.png" style="width: 160px; height: 30px;" />
                         </span>
                </div>
                <div class="logo-element">
                    <img alt="image" src="backend/img/logo_ss.png" style="width: 30px; height: 30px;" />
                </div>
            </li>
            <!--David William dropdown-->
            <!-- David Williams dropdown -->
            <li class="dropdown_1">
                <a data-toggle="dropdown" class="dropdown-toggle" style="color: #a7b1c2; background-color: #48505e;"href="#">
                <span class="clear">
                    <span class="block m-t-xs ">
                        <strong class="font-bold">Người dùng: David Williams</strong>
                    </span>
                    <span class="text-muted text-xs block">Art Director</span>
                </span>
                </a>
                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                    <li><a href="profile.html">Profile</a></li>
                    <li><a href="contacts.html">Contacts</a></li>
                    <li><a href="mailbox.html">Mailbox</a></li>
                    <li class="divider"></li>
                    <li><a href="login.html">Logout</a></li>
                </ul>
            </li>

            <li class="active">
                <a href="{{ route('dashboard.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Trang Quản Trị</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('user.index') }}">  <i class="fas fa-id-card"></i>Tài khoản</a></li>
                    <li><a href="index.html"><i class="fas fa-shield-alt"></i>Phân quyền</a></li>
                    <li><a href="index.html"> <i class="fas fa-address-book"></i>Nhân viên</a></li>
                    <li><a href="{{ route('backend.customer.index') }}"> <i class="fa-solid fa-users"></i>Khách hàng</a></li>
                    <li><a href="index.html"> <i class="fa-solid fa-clipboard"></i>Phòng ban</a></li>
                    <li><a href="index.html"> <i class="fa-solid fa-tools"></i>Yêu cầu</a></li>
                    <li><a href="index.html"> <i class="fa-solid fa-chart-line"></i>Thống kê</a></li>
                    <li><a href="index.html"> <i class="fa-solid fa-newspaper"></i>Tin tức</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

