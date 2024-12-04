<nav class="navbar-default navbar-static-side" role="navigation" style="">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header"> 
                <div class="dropdown profile-element open"> <span>
                    <img alt="image"  src="backend/img/logosweetsoft.png" 
                    style="width:170px; height = 100px; margin-top: -10px; margin-bottom: 20px">
                        <img alt="image" class="img-circle" src="backend/img/profile_small.jpg">
                         </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David Williams</strong>
                         </span> <span class="text-muted text-xs block"> <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="login.html">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="img-circle" src="backend/img/logo_ss.png" style="width:30px; height = 30px"> 
                </div>
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