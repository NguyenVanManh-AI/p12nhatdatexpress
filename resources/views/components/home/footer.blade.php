<footer id="footer-cntr">
    {!! $getConfig->footer !!}
    <div class="container-fluid">
        <div class="row footer">
            <div class="col-12 col-sm-12 col-md-12 col-xl-3 pd-15 information">
                <div class="logo-footer">
                    <a href="#"><img class="lazy" data-src="{{ asset($getConfig->logo1) }}"></a>
                </div>
                <div class="content-footer">
                    {!! nl2br($getConfig->text_footer) !!}
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-2 pd-15 quick-link">
                <h4 class="widget-title">Đường dẫn nhanh</h4>
                <ul class="log">
                    @if (auth()->guard('user')->check())
                        <li><a href="{{ route('user.personal-info') }}"><img
                                    src="{{ asset('frontend/images/tick.png') }}"><span>Tài khoản</span></a></li>
                    @else
                        <li><a href="#" class="btn-regis"><img
                                    src="{{ asset('frontend/images/tick.png') }}"><span>Đăng ký</span></a></li>
                        <li><a href="#" class="btn-login"><img
                                    src="{{ asset('frontend/images/tick.png') }}"><span>Đăng nhập</span></a></li>
                    @endif
                    <li><a href="/nha-dat-cho-thue"><img src="{{ asset('frontend/images/tick.png') }}"><span>Nhà đất cho
                                thuê</span></a></li>
                    <li><a href="/nha-dat-ban"><img src="{{ asset('frontend/images/tick.png') }}"><span>Nhà đất
                                bán</span></a></li>
                    <li><a href="/danh-ba/chuyen-vien-tu-van"><img
                                src="{{ asset('frontend/images/tick.png') }}"><span>Danh bạ</span></a></li>
                    @if (auth()->guard('user')->check())
                        <li><a href="{{ route('user.add-classified', 'nha-dat-ban') }}" style="background: none"
                                class="btn-post-after-login" java><img
                                    src="{{ asset('frontend/images/tick.png') }}"><span>Đăng tin</span></a></li>
                    @else
                        <li><a href="#" class="js-open-add-classified__login"><img
                                    src="{{ asset('frontend/images/tick.png') }}"><span>Đăng tin</span></a></li>
                    @endif
                </ul>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 pd-15 address">
                <h4 class="widget-title">Công ty cpđt & công nghệ nhà đất express</h4>

                <ul class="c-mb-10">
                    @foreach (preg_split("/\r\n|\n|\r/", $getConfig->info_company) as $companyInfo)
                        <li>
                            @switch($loop->index)
                                @case(0)
                                <i class="fas fa-map-marker-alt"></i>
                                @break
                                @case(1)
                                <i class="fas fa-phone-alt"></i>
                                @break
                                @case(2)
                                <i class="far fa-envelope"></i>
                                @break
                                @case(3)
                                <i class="fas fa-globe"></i>
                                @break
                                @default
                            @endswitch
                            <span>{{ trim($companyInfo) }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="d-none" id="info-company">{{ $getConfig->info_company }}</div>
                <ul id="list-info" class="list-address">
                </ul>

                <?php
                    $socials = [
                        [
                            'type' => 'facebook',
                            'link' => $getConfig->facebook,
                            'icon' => 'fab fa-facebook-f',
                        ],
                        [
                            'type' => 'youtube',
                            'link' => $getConfig->youtube,
                            'icon' => 'fab fa-youtube',
                        ],
                        [
                            'type' => 'linkedin',
                            'link' => $getConfig->linkedlin,
                            'icon' => 'fab fa-linkedin-in',
                        ],
                    ];
                ?>

                <x-common.social-link
                    :socials="$socials"
                />

                {{-- <div class="social-link__box">
                    <ul>
                        <li class="social-link__item mr-3">
                            <a href="{{ $getConfig->facebook }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
                                <img class="object-cover rounded-circle" src="{{ asset('frontend/images/facebook.png') }}">
                            </a>
                        </li>
                        <li class="social-link__item mr-3">
                            <a href="{{ $getConfig->youtube }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
                                <img class="object-cover rounded-circle" src="{{ asset('frontend/images/Youtube.png') }}">
                            </a>
                        </li>
                        <li class="social-link__item">
                            <a href="{{ $getConfig->linkedlin }}" class="d-inline-block icon-squad-32 hover-scale" target="_blank">
                                <img class="object-cover rounded-circle" src="{{ asset('frontend/images/Logotikktok.png') }}">
                            </a>
                        </li>
                    </ul>
                </div> --}}
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3 pd-15 contact">
                <h4 class="widget-title">Nhận bảng tin Express</h4>
                <div class="contact-form">
                    <form class="form-search">
                        <input type="text" name="s" class="form-control search-input"
                            placeholder="Nhập email của bạn">
                        <button class="btn-submit btn-register-footer">
                            Đăng ký
                        </button>
                    </form>
                </div>
                <div class="app-download">
                    <a target="_blank" href="{{ $getConfig->apple_store }}"><img
                            src="{{ asset('frontend/images/Appstore.png') }}"></a>
                    <a target="_blank" href="{{ $getConfig->ch_play }}"><img src="{{ asset('frontend/images/Google-play.png') }}"></a>
                </div>
                <div class="logo-industry">
                    <img class="lazy" data-src="{{ asset($getConfig->logo2) }}">
                    <img class="lazy" data-src="{{ asset($getConfig->logo3) }}">
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .container1 {

            display: flex;
            flex-flow: row wrap;
            align-content: space-between;
            justify-content: space-between;
        }

        .item1 {
            text-align: center;
            margin: 10px;
        }
    </style>
    <div class="center-footer">
        <div class="text-center">
            {!! nl2br($getConfig->introduce) !!}
        </div>
    </div>
    <div class="menu-footer text-center">
        <div class="container-fluid">
            <ul class="container1">
                @foreach ($getStaticMenu as $item)
                    <li class="item1"><a href="/{{ $item->page_url }}"><img
                                src="{{ asset('frontend/images/Icon Footer.png') }}"><span>{{ $item->page_title }}</span></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="copy-right text-center">
        <p>Copyright @ {{ now()->year }} Nhadatexpress - All rights reserved.</p>
    </div>

</footer>
