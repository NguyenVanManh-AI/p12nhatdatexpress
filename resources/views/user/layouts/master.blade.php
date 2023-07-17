<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>@yield('title', 'Nhà đất express')</title>
        <link rel="icon" type="image/x-icon" href="{{asset(SystemConfig::logo())}}">
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}

        <link rel="stylesheet" href="{{ asset('common/plugins/ionicons/ionicons.min.css') }}">

        {{-- admin lte3 | should check plugins not use then remove it --}}
        <link rel="stylesheet" href="{{ asset('common/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/sweetalert2/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/toastr/toastr.min.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('common/plugins/jqvmap/jqvmap.min.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('common/plugins/ekko-lightbox/ekko-lightbox.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/summernote/summernote-bs4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <link rel="stylesheet" href="{{ asset('common/adminlte/css/adminlte.min.css') }}">
        {{-- end admin lte3 --}}

        <link rel="stylesheet" href="{{ asset('common/plugins/fancybox/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" href="{{asset('user/vendor/jquery-ui.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/rating.css')}}">
        <link rel="stylesheet" href="{{ asset('user/vendor/owl-carousel-2.3.4/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('user/vendor/owl-carousel-2.3.4/owl.theme.custom.css') }}">

        <link rel="stylesheet" href="{{asset('user/vendor/slick.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/main.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/plus1.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/plus2.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/plus3.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/plusb.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/responsive.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/responsiveb.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/plushn.css')}}">
        <link rel="stylesheet" href="{{asset('user/vendor/responsivehn.css')}}">
        <link rel="stylesheet" href="{{asset('user/css/main.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/css/global.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/css/global-custom.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/css/main/page.css')}}">
        <link rel="stylesheet" href="{{asset('user/css/page.css')}}">
        <link rel="stylesheet" href="{{ asset('frontend/css/variables.css') }}">

        @if(auth('user')->user())
	        <link rel="stylesheet" href="{{ mix("css/chat-box.css") }}" as="style">
        @endif

        @section('css')@show
    </head>
    <body class="page-manager-account">
        <main class="main">
            @include('user.layouts.header')
            <div class="offer-list-code">
                <div class="">
                    <div class="no-gutters layout-web">
                        <div class="sidebar px-0 scrollable">
                            @include('user.layouts.sidebar')
                        </div>
                        <div class="tutorial manager-content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="user-content-wrapper pb-3">
                                            @section('content')@show
                                        </div>
                                        @include('user.layouts.footer')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="over-lay-1"></div>
        </main>
        <!-- /.content-wrapper -->
        <!-- Layer -->
        <div id="layout"></div>
        <div class="overlay"></div>

        <!-- Popup -->
        @if(!auth()->guard('user')->user()->user_type_id)
            <x-user.choose-user-type></x-user.choose-user-type>
        @endif
        @section('popup')@show

        {{-- chat popup --}}
        @if(auth('user')->user())
            <div id="chat-boxes" class="border hover-shadow rounded {{ session('opened-conversation') ? 'opened minimum' : '' }}"
                data-token="{{ session('opened-conversation.token') }}"
            >
                @if(session('opened-conversation.avatar'))
                    <span class="mini-icon">
                        <x-user.avatar
                            width="60"
                            height="60"
                            rounded="30"
                            :is-fancy-box="false"
                            avatar="{{ session('opened-conversation.avatar') }}"
                        />
                    </span>
                @else
                    <span class="mini-icon">
                    <i class="fas fa-comments"></i>
                    </span>
                @endif
            </div>
        @endif

        {{-- admin lte3 | should check plugins not use then remove it --}}
        <script src="{{ asset('common/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('common/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('common/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('common/plugins/select2/js/i18n/vi.js') }}"></script>
        <script src="{{ asset('common/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('common/plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('common/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('common/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('common/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        {{-- <script src="{{ asset('common/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
        <script src="{{ asset('common/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> --}}
        <script src="{{ asset('common/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
        <script src="{{ asset('common/plugins/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('common/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
        <script src="{{ asset('common/plugins/summernote/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('common/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('common/adminlte/js/adminlte.min.js') }}"></script>
        {{-- end admin lte3 --}}

        <script src="{{asset('user/vendor/jquery-ui-1.13.2/jquery-ui.min.js')}}"></script>

        <script src="{{ asset('common/plugins/fancybox/jquery.fancybox.js') }}"></script>
        <script src="{{asset('user/vendor/wow.min.js')}}"></script>
        <script src="{{asset('frontend/plugins/html5lightbox/froogaloop2.min.js')}}"></script>
        <script src="{{asset('frontend/plugins/html5lightbox/html5lightbox.js')}}"></script>
        <script src="{{asset('user/vendor/ekko-lightbox/ekko-lightbox.js')}}"></script>
        <script src="{{asset('user/vendor/css-element-queries-master/tests/src/ElementQueries.js')}}"></script>
        <script src="{{ asset('user/vendor/owl-carousel-2.3.4/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('common/plugins/jquery.lazy.1.7.9/jquery.lazy.min.js') }}"></script>
        <script src="{{ asset('common/plugins/jquery.lazy.1.7.9/jquery.lazy.plugins.min.js') }}"></script>

        <script src="{{ asset('user/vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('system/js/tinymce4x/vi_VN.js') }}"></script>

        {{-- all owl carousel for user dashboard page --}}
        <script src="{{ asset('user/vendor/page-owl-carousel.js') }}"></script>
        <script src="{{asset('user/vendor/slick.js')}}"></script>

        {{-- plugin init all fancybox,tinimce --}}
        <script src="{{ asset('common/js/page-plugin-init.js') }}"></script>

        <script src="{{asset('frontend/js/app/app.js')}}"></script>

        <script src="{{asset('user/vendor/plus1.js')}}"></script>
        <script src="{{asset('user/vendor/plus2.js')}}"></script>
        <script src="{{asset('user/vendor/plusb.js')}}"></script>
        <script src="{{asset('user/vendor/plushao.js')}}"></script>
        <script src="{{asset('user/js/main.js')}}"></script>

        {{-- <script src="{{ asset('frontend/js/custom.js') }}"></script> --}}
        <script src="{{ asset('frontend/js/global/main.js') }}"></script>

        @section('script') @show()
        @stack('scripts_children')
        <script>
            $(document).ready(function () {
                open_current_popup("{{Session::get('popup')}}");
            })
        </script>
        {!! Toastr::message() !!}

        @if(auth('user')->user())
            {{-- chat-box --}}
            <script type="text/javascript">
                const SOCKET_HOST_URL = window.location.hostname + '{{ config('app.env') == 'local' ? ':6001' : '' }}'
                const USER_CHANNEL_TOKEN = "{{ auth('user')->user()->channel_token ?? '' }}"
                const ACTIVE_ONVERSATION_TOKEN = '';
            </script>
            <script src="{{ mix("js/chat-box.js") }}"></script>
        @endif
    </body>
</html>
