<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.thongke') }}" id="logo">
        <img style="margin-left: 0.8rem" src="{{asset('frontend/images/logo-white.png')}}" alt="logo" class="brand-image">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
              {{-- should change --}}
              <img src="{{asset('system/img/avatar-admin/'. Auth::guard('admin')->user()->image_url)}}" class="img-circle elevation-2" style="height:2.1rem">
            </div>
            <div class="info">
                <span class="text-white">Hello, </span>
                <a href="javascript:{}" class="d-block" style="font-weight: 500">{{Auth::guard('admin')->user()->admin_fullname}}</a>
            </div>
        </div>

        <!-- 27/12/21: Khang comment - SidebarSearch Form
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
        -->
        <x-admin.sidebar.menu />
    </div>
    <!-- /.sidebar -->
</aside>
