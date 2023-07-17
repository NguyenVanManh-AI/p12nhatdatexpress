<header id="header-cntr" class="border-bottom">
  <div class="header-box">
    <div class="header-inner d-flex flex-start py-2 px-3">
      <div class="header-logo-box h-100 text-center">
        <a href="{{ route('home.index') }}" class="d-inline-block h-100 mw-100">
          <img class="object-cover" src="{{ asset(SystemConfig::logo()) }}" alt="">
        </a>
      </div>
      <div class="header-open-sidebar-box h-100 flex-start">
        <a href="javascript:void(0);" class="toggle-sidebar-header text-dark flex-center hover-bg-gray h-100">
          <i class="fas fa-align-justify"></i>
        </a>
      </div>
      <div class="header-action-box h-100 flex-1 flex-end">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-0 h-100">
          <ul class="navbar-nav ml-auto h-100">
            <x-user.notify class="mr-2"/>
            <x-user.mail class="mr-3"/>
            <x-user.avatar
              :avatar="asset(auth('user')->user()->detail->avatar ?? '')"
              :link="route('user.personal-info')"
            />
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>
