<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light main-blue">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars mr-1"></i></a>
        </li>
        <li class="nav-item  d-sm-inline-block">
            <span id="current-time">00:00:00</span>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="{{url('/')}}" role="button">
                <i class="fas fa-globe mr-1"></i>
                <span class="d-none d-md-inline">Xem website</span>
            </a>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown d-none d-md-inline">
            <a class="nav-link dropdown-toggle mr-2" data-toggle="dropdown" href="#">
                <i class="fas fa-language mr-1"></i>
                Ngôn ngữ admin Việt Nam
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="javascript:{}" class="dropdown-item">
                    Data English
                </a>
            </div>
        </li>

        <li class="nav-item dropdown d-none d-md-inline">
            <a class="nav-link dropdown-toggle mr-2" data-toggle="dropdown" href="#">
                <i class="fas fa-language mr-1"></i>
                Dữ liệu Việt Nam
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="javascript:{}" class="dropdown-item">
                    Data English
                </a>
            </div>
        </li>

        <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle mr-2" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-user mr-1"></i>
                <span class="d-none d-md-inline">{{Auth::guard('admin')->user()->admin_fullname}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{route('admin.logout')}}" class="dropdown-item">
                    Đăng xuất
                </a>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
