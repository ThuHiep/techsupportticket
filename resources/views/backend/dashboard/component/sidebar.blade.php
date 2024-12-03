<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                         </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David Williams</strong>
                         </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="login.html">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li class="active">
                <a href="href="{{ route('dashboard.index') }}""><i class="fa fa-th-large"></i> <span class="nav-label">Trang Quản Trị</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('user.index') }}">Tài khoản</a></li>
                    <li><a href="index.html">Phân quyền</a></li>
                    <li><a href="index.html">Nhân viên</a></li>
                    <li><a href="index.html">Khách hàng</a></li>
                    <li><a href="index.html">Phòng ban</a></li>
                    <li><a href="index.html">Yêu cầu</a></li>
                    <li><a href="index.html">Thống kê</a></li>
                    <li><a href="index.html">Tin tức</a></li>
                </ul>
            </li>
        </ul>

    </div>
</nav>