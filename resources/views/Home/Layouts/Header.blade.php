<header id="home-header-cntr">
  <div class="site-header-container container">
    <div class="banner home-header__banner">
      <div class="left pr-2">
        <a href="{{url('/' . $categories_home[0]->group_url)}}">
            <img src="{{ asset($system_config->logo4) }}">
        </a>
      </div>
      <div class="right w-100 h-100">
        <a href="/">
            <img src="{{ asset($system_config->banner) }}">
        </a>
      </div>
    </div>

    <div class="header-bottom">
        <div class="menu-bottom-box flex-between">
            <div class="small-logo-box flex-center">
                <a class="logo-inner" href="{{ url('/' . data_get($categories_home, '0.group_url', 'trang-chu')) }}">
                    <img class="logo-image" src="{{ asset('frontend/images/Home.png') }}"></a>
                </a>
            </div>
            <div class="nav-menu-box flex-1">
                <ul class="nav-menu flex-wrap">
                    @foreach ($categories_home as $item)
                        @if ($item->id == 1)
                            @continue
                        @endif
    
                        <li class="nav-item">
                            @if ($item->group_url == 'danh-ba')
                                <a href="javascript:void(0);">{{ $item->group_name }}</a>
                            @else
                                <a href="{{ url('/' . $item->group_url) }}">{{ $item->group_name }}</a>
                            @endif
    
                            @if (count($item->children) > 0)
                                <ul class="sub-menu">
                                    @foreach ($item->children as $child)
                                        <li class="{{ count($child->children) > 0 ? 'has-sub-menu' : '' }}">
                                            <a href="{{ url('/' . $item->group_url . '/' . $child->group_url) }}"><span>{{ $child->group_name }}</span>
                                            </a>
                                            @if (count($child->children) > 0)
                                                <ul class="sub-menu">
                                                    @foreach ($child->children as $child_of_child)
                                                        <li>
                                                            <a href="{{ url('/' . $item->group_url . '/' . $child_of_child->group_url) }}">
                                                                <span>{{ $child_of_child->group_name }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bottom-action relative flex-auto pr-2 js-need-toggle-active">
                <div class="lg-icon align-item-center justify-content-center d-flex d-lg-none">
                    <a href="#" class="js-toggle-active text-white fs-18">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                <div class="action-menu js-toggle-area">
                    <div class="flex-center">
                        @if (auth()->guard('user')->check())
                            <a href="{{ route('user.add-classified', 'nha-dat-ban') }}" class="btn btn-success ml-2 py-1">
                                <span class="btn-icon flex-inline-center mr-1">
                                    <i class="fas fa-plus"></i>
                                </span>
                                Đăng tin
                            </a>
                            <a href="{{ route('user.personal-info') }}" target="_blank" class="btn btn-purple ml-2 py-1">
                                <span class="btn-icon flex-inline-center mr-1 text-dark">
                                    <i class="fas fa-sign-in-alt"></i>
                                </span>
                                Tài khoản
                            </a>
                        @else
                            <a href="#" class="js-open-add-classified__login btn-post btn btn-success ml-2 py-1">
                                <span class="btn-icon flex-inline-center mr-1">
                                    <i class="fas fa-plus"></i>
                                </span>
                                Đăng tin
                            </a>
                            <a href="#" class="btn btn-primary js-open-login ml-2 py-1" data-toggle="modal" data-target="#login-modal">
                                <span class="btn-icon flex-inline-center mr-1 text-dark">
                                    <i class="fas fa-sign-in-alt"></i>
                                </span>
                                Đăng nhập
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="menu">
      <div class="small-logo-box flex-center">
        <a class="logo-inner" href="{{ url('/' . data_get($categories_home, '0.group_url', 'trang-chu')) }}">
          <img class="logo-image" src="{{ asset('frontend/images/Home.png') }}"></a>
        </a>
      </div>
      <div class="main-menu">
        <ul class="nav-menu">
            @foreach($categories_home as $item)
                @if($item->id == 1)
                    @continue
                @endif

                <li>
                    @if($item->group_url == 'danh-ba')
                        <a  href="javascript:{}">{{$item->group_name}}</a>
                    @else
                        <a  href="{{url('/'. $item->group_url)}}">{{$item->group_name}}</a>
                    @endif

                    @if(count($item->children) > 0)
                        <ul class="sub-menu">
                            @foreach($item->children as $child)
                                <li class="{{count($child->children) > 0 ? "menu-item-has-children" : ''}}">
                                    <a href="{{url('/'.$item->group_url.'/'.$child->group_url)}}"><span>{{$child->group_name}}</span></a>
                                    @if(count($child->children) > 0)
                                        <ul class="sub-menu">
                                            @foreach($child->children as $child_of_child)
                                                <li><a href="{{url('/'.$item->group_url.'/'.$child_of_child->group_url)}}"><span>{{$child_of_child->group_name}}</span></a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
      </div>

      <div class="group-btn">
          @if(auth()->guard('user')->check())
              <a href="{{route('user.add-classified', 'nha-dat-ban')}}" class="btn-post-after-login" java><img src="{{asset('frontend/images/Icon post.png')}}" ><span>Đăng tin</span></a>
                <a href="{{route('user.personal-info')}}" class="btn btn-warning"><img src="{{asset('frontend/images/Icon login.png')}}"><span>Tài khoản</span></a>
          @else
              <a href="#" class="btn-post"><img src="{{asset('frontend/images/Icon post.png')}}"><span>Đăng tin</span></a>
              <a href="#" class="btn-login"><img src="{{asset('frontend/images/Icon login.png')}}"><span>Đăng nhập</span></a>
          @endif

          @auth
              <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-primary font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      {{\Illuminate\Support\Facades\Auth::user()->user_detail->fullname}}
                  </button>
                  <div class="dropdown-menu">
                      <a class="dropdown-item text-dark" href="{{route('oneclick-logout')}}">Đăng xuất</a>
                  </div>
              </div>
          @endauth
      </div>
    </div> --}}
  </div>
  <div class="site-header-mobile container border-bottom h-100">
    <div class="wrapper h-100">
      <div class="search"><i class="fas fa-search"></i></div>
      <div class="logo">
        <a href="/"><img src="{{ asset(SystemConfig::logo()) }}"></a>
      </div>
      <div class="m-menu-icon">
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </div>
</header>
<div id="m-menu">
    <div class="wrapper">
        <div class="top">
            <div class="user-avatar">
                @if(auth()->guard('user')->check())
                <a href="{{route('user.personal-info')}}">
                    <img src="{{asset('frontend/images/no_avatar.png')}}">
                </a>
                @else
                    <img src="{{asset('frontend/images/no_avatar.png')}}">
                @endif
            </div>
            <div class="log flex-wrap">
                @if(auth()->guard('user')->check())
                    <div class="post-button">
                        <i class="fas fa-plus-circle"></i>
                        Đăng tin
                    </div>
                @else
                    <a href="#" class="btn-login mb-3">Đăng nhập</a>
                    <a href="#" class="btn-regis mb-3">Đăng ký</a>
                    <div class="post-button js-open-add-classified__login" data-dismiss="modal">
                        <i class="fas fa-plus-circle"></i>
                        Đăng tin
                    </div>
                @endif
            </div>
        </div>
        <ul class="menu-list">
{{--            @if($item->id == 0) <i class="fas fa-home"></i> @endif--}}
            @foreach($categories_home as $item)
            <li class="{{count($item->children) > 0 ? 'has-child' : ''}}">
                @if($item->group_url == 'danh-ba')

                <a href="javascript:{}"> {{$item->group_name}}</a>
                @else
                <a href="/{{$item->group_url}}"> {{$item->group_name}}</a>
                @endif
                    @if(count($item->children) > 0)
                <ul class="sub">
                    @foreach($item->children as $child)
                    <li class="{{count($child->children) > 0 ? 'has-child' : ''}}">
                        <a href="/{{$item->group_url}}/{{$child->group_url}}">{{$child->group_name}}</a>
                        @if(count($child->children) > 0)
                        <ul class="sub" style="display: block;">
                            @foreach($child->children as $child_of_child)
                                <li><a href="/{{$item->group_url}}/{{$child->group_url}}/{{$child_of_child->group_url}}">{{$child_of_child->group_name}}</a></li>
                            @endforeach
                        </ul>
                        <i class="fas fa-chevron-down"></i>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>
