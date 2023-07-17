<div class="content-left-account dashboard-sidebar-box">
    <div class="widget widget-account">
        <div class="account-business-info" >
            <div class="account-business-profile">
                <x-user.user-info-sidebar-component />
            </div>
            <div class="info-account">
                <P>Tài khoản: <span>{{number_format(auth()->guard('user')->user()->coint_amount)}} Coin</span></P>
                <a href="{{route('user.deposit')}}"><i class="fas fa-plus-circle"></i> Nạp tiền</a>
            </div>
        </div>
        <div class="list-item-account">
            <div class="item-account {{ Route::currentRouteName() == 'user.index' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.index')}}">
                        <span class="mr-1">
                            <i class="fas fa-cogs"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon1.png')}}" alt=""> --}}
                        Thống kê dữ liệu
                    </a>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.personal-info' ? 'is-active' : '' }}">
                <div class="item-account-parent"><a class="link-a" href="{{route('user.personal-info')}}">
                        <span class="mr-1">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon2.png')}}" alt=""> --}}
                        <span class="position-relative">
                            Thông tin tài khoản
                            @if(!auth('user')->user()->isActive())
                                <span class="position-absolute top-0 start-100 translate-y-middle badge badge-pill badge-danger">!</span>
                            @endif
                        </span>
                    </a>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.level' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.level')}}">
                        <span class="mr-1">
                            <i class="fas fa-share-alt"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon3.png')}}" alt=""> --}}
                        Quản lý cấp bậc
                    </a>
                </div>
            </div>
            <div class="item-account {{ in_array(Route::currentRouteName(), ['user.package', 'user.add-classified', 'user.list-classified']) ? 'is-open' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="#">
                        <span class="mr-1">
                            <i class="fas fa-align-justify"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon4.png')}}" alt=""> --}}
                        Quản lý tin đăng
                        <div class="Notification">
                            <i class="fas fa-chevron-right open-icon"></i>
                        </div>
                    </a>
                </div>
                <div class="menu-child {{ in_array(Route::currentRouteName(), ['user.package', 'user.add-classified', 'user.list-classified']) ? 'active' : '' }}">
                    <ul class="list-menu-child">
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.package' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.package')}}">Trạng thái</a>
                        </li>
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.add-classified' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.add-classified', 'nha-dat-ban')}}">Đăng tin mới</a>
                        </li>
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.list-classified' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.list-classified')}}">Danh sách tin đăng</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.customer' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.customer')}}">
                        <span class="mr-1">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon5.png')}}" alt=""> --}}
                        Quản lý khách hàng
                    </a>
                </div>
            </div>
            @if(auth('user')->user()->user_type_id == 3)
                <div class="item-account {{ Route::currentRouteName() == 'user.events.index' ? 'is-active' : '' }}">
                    <div class="item-account-parent">
                        <a class="link-a" href="{{route('user.events.index')}}">
                            <span class="mr-1">
                                <i class="fas fa-bookmark"></i>
                            </span>
                            {{-- <img src="{{asset('user/images/sidebar-icon/icon6.png')}}" alt=""> --}}
                            Quản lý sự kiện
                        </a>
                    </div>
                </div>
            @endif
            <?php
                $userSidebar['mail_marketing'] = [
                    'user.template-mail',
                    'user.edit-template-mail',
                    'user.campaigns.create',
                    'user.campaigns.index',
                    'user.campaigns.edit',
                    'user.campaigns.view-details',
                    'user.config-mail',
                    'user.edit-config-mail'
                ];
            ?>
            @if(getEnabledMailCampaign())
                <div class="item-account {{ in_array(Route::currentRouteName(), $userSidebar['mail_marketing']) ? 'is-open' : '' }}">
                    <div class="item-account-parent">
                        <a class="link-a" href="#">
                            <span class="mr-1">
                                <i class="fas fa-envelope"></i>
                            </span>
                            {{-- <img src="{{asset('user/images/sidebar-icon/icon7.png')}}" alt=""> --}}
                            Email Marketing
                            <div class="Notification">
                                <i class="fas fa-chevron-right open-icon"></i>
                            </div>
                        </a>
                    </div>
                    <div class="menu-child {{ in_array(Route::currentRouteName(), $userSidebar['mail_marketing']) ? 'active' : '' }}">
                        <ul class="list-menu-child">
                            <li class="item-menu-child {{ in_array(Route::currentRouteName(), ['user.template-mail', 'user.edit-template-mail']) ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.template-mail') }}">Tạo mail mới</a>
                            </li>
                            <li class="item-menu-child {{ Route::currentRouteName() == 'user.campaigns.create' ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.campaigns.create') }}">Tạo chiến dịch</a>
                            </li>
                            <li class="item-menu-child {{ in_array(Route::currentRouteName(), ['user.campaigns.index', 'user.campaigns.edit', 'user.campaigns.view-details']) ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.campaigns.index') }}">Quản lý chiến dịch</a>
                            </li>
                            <li class="item-menu-child {{ in_array(Route::currentRouteName(), ['user.config-mail', 'user.edit-config-mail']) ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{route('user.config-mail')}}">Cấu hình mail</a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
            <div class="item-account {{ in_array(Route::currentRouteName(), ['user.deposit', 'user.transaction', 'user.invoice']) ? 'is-open' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="#">
                        <span class="mr-1">
                            <i class="fas fa-coins"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon8.png')}}" alt=""> --}}
                        Quản lý tài chính
                        <div class="Notification">
                            <i class="fas fa-chevron-right open-icon"></i>
                        </div>
                    </a>
                </div>
                <div class="menu-child {{ in_array(Route::currentRouteName(), ['user.deposit', 'user.transaction', 'user.invoice']) ? 'active' : '' }}">
                    <ul class="list-menu-child">
                        <ul class="list-menu-child">
                            <li class="item-menu-child {{ Route::currentRouteName() == 'user.deposit' ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.deposit') }}">Thông tin tài khoản</a>
                            </li>
                            <li class="item-menu-child {{ Route::currentRouteName() == 'user.transaction' ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.transaction') }}">Lịch sử giao dịch</a>
                            </li>
                            <li class="item-menu-child {{ Route::currentRouteName() == 'user.invoice' ? 'is-active' : '' }}">
                                <span class="item-menu-child__icon flex-center">
                                    <i class="fas fa-circle nav-icon"></i>
                                </span>
                                <a class="link-a-child" href="{{ route('user.invoice') }}">Hóa đơn</a>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
            <div class="item-account {{ in_array(Route::currentRouteName(), ['user.add-express', 'user.express']) ? 'is-open' : '' }}">
               <div class="item-account-parent">
                   <a class="link-a" href="#">
                        <span class="mr-1">
                            <i class="far fa-chart-bar"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon9.png')}}" alt=""> --}}
                        Quảng cáo Express
                        <div class="Notification">
                            <i class="fas fa-chevron-right open-icon"></i>
                        </div>
                   </a>
               </div>
               <div class="menu-child {{ in_array(Route::currentRouteName(), ['user.add-express', 'user.express']) ? 'active' : '' }}">
                    <ul class="list-menu-child">
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.add-express' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.add-express')}}">Tạo chiến dịch</a>
                        </li>
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.express' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.express')}}">Quản lý chiến dịch</a>
                        </li>
                   </ul>
               </div>
            </div>
            @if(auth()->guard('user')->user()->user_type_id == 2 || auth()->guard('user')->user()->user_type_id == 3)
                <div class="item-account {{ Route::currentRouteName() == 'user.reference' ? 'is-active' : '' }}">
                    <div class="item-account-parent">
                        <a class="link-a" href="{{route('user.reference')}}">
                            <span class="mr-1">
                                {{-- <i class="fas fa-lock"></i> --}}
                                <i class="fas fa-link"></i>
                            </span>
                            {{-- <img src="{{asset('user/images/sidebar-icon/icon11.png')}}" alt=""> --}}
                            Liên kết tài khoản
                        </a>
                    </div>
                </div>
            @endif
            <div class="item-account {{ Route::currentRouteName() == 'user.user-ref-list' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.user-ref-list')}}">
                        <span class="mr-1">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon12.png')}}" alt=""> --}}
                        Giới thiệu người dùng
                    </a>
                </div>
            </div>
            <div class="item-account {{ in_array(Route::currentRouteName(), ['user.promotion', 'user.promotion-news']) ? 'is-open' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="#">
                        <span class="mr-1">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon13.png')}}" alt=""> --}}
                        Ưu đãi
                        <div class="Notification">
                            <i class="fas fa-chevron-right open-icon"></i>
                        </div>
                    </a>
                </div>
                <div class="menu-child {{ in_array(Route::currentRouteName(), ['user.promotion', 'user.promotion-news']) ? 'active' : '' }}">
                    <ul class="list-menu-child">
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.promotion' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.promotion')}}">Danh sách mã </a>
                        </li>
                        <li class="item-menu-child {{ Route::currentRouteName() == 'user.promotion-news' ? 'is-active' : '' }}">
                            <span class="item-menu-child__icon flex-center">
                                <i class="fas fa-circle nav-icon"></i>
                            </span>
                            <a class="link-a-child" href="{{route('user.promotion-news')}}">Thông tin ưu đãi</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.gallery' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.gallery')}}">
                        <span class="mr-1">
                            <i class="fas fa-folder"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon14.png')}}" alt=""> --}}
                        Thư viện
                    </a>
                </div>
            </div>
            <div class="item-account">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.guide')}}">
                        <span class="mr-1">
                            <i class="fas fa-lightbulb"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon15.png')}}" alt=""> --}}
                        Hướng dẫn
                    </a>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.support' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.supports.index')}}">
                        <span class="mr-1">
                            <i class="fas fa-tools"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon16.png')}}" alt=""> --}}
                        <span class="position-relative">
                            Hỗ trợ kỹ thuật
                            <span class="chat-box__show-unread-message position-absolute top-0 start-100 translate-y-middle badge badge-pill badge-danger {{ !auth('user')->user()->getUnreadMessages() ? 'd-none' : '' }}">{{ auth('user')->user()->getUnreadMessages() }}</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.mailbox' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.mailbox')}}">
                        <span class="mr-1">
                            <i class="fas fa-inbox"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon17.png')}}" alt=""> --}}
                        Hòm thư
                    </a>
                </div>
            </div>
            <div class="item-account {{ Route::currentRouteName() == 'user.social' ? 'is-active' : '' }}">
                <div class="item-account-parent">
                    <a class="link-a" href="{{route('user.social')}}">
                        <span class="mr-1">
                            <i class="fas fa-users"></i>
                        </span>
                        {{-- <img src="{{asset('user/images/sidebar-icon/icon18.png')}}" alt=""> --}}
                        Cộng đồng
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-footer" >
        <div class="sidebar-footer-item"><a href="{{route('user.mailbox')}}"><i class="far fa-envelope"></i></a></div>
        <div class="sidebar-footer-item"><a href="{{route('user.personal-info')}}"><i class="fas fa-user"></i></a></div>
        <div class="sidebar-footer-item"><a href="{{route('user.index')}}"><i class="fas fa-cog"></i></a></div>
        <div class="sidebar-footer-item"><a href="{{route('user.logout')}}"><i class="fas fa-sign-out-alt"></i></a></div>
        <div class="sidebar-footer-item"><a href="{{route('user.social')}}"><i class="fas fa-comment"></i></a></div>
    </div>
</div>



