<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @php
        $info_website = \Illuminate\Support\Facades\Cache::rememberForever('system_config', function() {
            return  \Illuminate\Support\Facades\DB::table('system_config')->first();
        });
    @endphp
    <meta name="description" content="@yield('Description',$info_website->introduce??'Nhà đất express')"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('Title','Nhà đất express')</title>
    <link rel="icon" type="image/x-icon" href="{{asset(SystemConfig::logo())}}">
    <meta name="description" content="@yield('Description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta name="keywords" content="@yield('Keywords','Nhà đất express')">
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="@yield('Title','Nhà đất express')">
    <meta itemprop="description" content="@yield('Description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta itemprop="image" content="@yield('Image',asset(SystemConfig::logo()))">
    <meta itemprop="url" content="@yield('Url',request()->fullUrl())">

    <!-- Facebook Meta Tags -->
    <meta property="og:url"         content="@yield('Url', request()->fullUrl())">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="@yield('Title', data_get($home_seo_config, 'meta_title', 'Nhà đất express'))">
    <meta property="og:description" content="@yield('Description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta property="og:image"       content="@yield('Image', $home_seo_config ? $home_seo_config->getSeoBanner() : asset(SystemConfig::logo()))">
    <!-- END facebook SEO-->

    {{-- roboto font --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> --}}

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="{{ asset('fonts/_source-sans-pro-font.css') }}"> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
        
    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/fontawesome-free/css/all.min.css')}}"> --}}
    <!-- Ion-rangeslider -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}"> --}}
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css')}}"> --}}
    <!-- iCheck -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> --}}
    <!-- JQVMap -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/jqvmap/jqvmap.min.css')}}"> --}}
    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/dist/css/adminlte.css')}}"> --}}
    <!-- overlayScrollbars -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}"> --}}
    <!-- Daterange picker -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/daterangepicker/daterangepicker.css')}}"> --}}

    <!-- LightBox -->
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/ekko-lightbox/ekko-lightbox.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{asset('frontend/css/owl.carousel.css')}}"> --}}
    <!--Date Calendar-->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/> --}}
    {{-- <link rel="stylesheet" href="{{asset('frontend/plugins/select2/css/select2.min.css')}}"> --}}

    <link rel="stylesheet" href="{{ asset('common/plugins/ionicons/ionicons.min.css') }}">

    {{-- admin lte3 | should check plugins not use then remove it --}}
    <link rel="stylesheet" href="{{ asset('common/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('common/plugins/sweetalert2/sweetalert2.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('common/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('common/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('common/plugins/summernote/summernote-bs4.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('common/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/adminlte/css/adminlte.min.css') }}">
    {{-- end admin lte3 --}}

    <link rel="stylesheet" href="{{ asset('common/plugins/fancybox/jquery.fancybox.min.css') }}">

    <link rel="stylesheet" href="{{ asset('common/plugins/animate/animate.min.css') }}">

    <link rel="stylesheet" href="{{ asset('user/vendor/owl-carousel-2.3.4/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/vendor/owl-carousel-2.3.4/owl.theme.custom.css') }}">

    <link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('frontend/css/variables.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('frontend/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/plus1.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/plus2.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/plus3.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/plusb.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/plushn.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/responsiveb.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/responsivehn.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/responsiveh.css')}}">

    <!-- Toastr -->
    {{-- <link rel="stylesheet" href="{{asset('system/css/toastr/toastr.min.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('frontend/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/global-custom.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/global.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/main/header-footer.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/main/page.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/home/page.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/variables.css')}}">

    @if(auth('user')->user())
        <link rel="stylesheet" href="{{ mix("css/chat-box.css") }}" as="style">
    @endif

    @section('Style')@show
    @stack('style')
</head>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script type="text/javascript"> new WOW().init();</script> -->
<body class="hold-transition">
<div class="wrapper">
    <div class="clearfix position-relative">
        <x-home.banner/>
        @include('Home.Layouts.Header')
        <div id="main-content" class="main">
            <main class="main">
                <!-- /.navbar -->
                <!-- Main Sidebar Container -->
                <div class="home-page">
                    <div class="container">
                        @section('Content')@show
                        <x-home.global-tag/>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<!-- Layer -->

{{-- loading overlay --}}
<x-common.loading/>

<div id="layout"></div>
<div class="overlay"></div>

<x-home.classified.popup-select />
{{--<x-home.classified.popup-create />--}}

{{--  --}}

<x-home.classified.guest-add-popup />


{{--  --}}


<x-home.project.view-map />
<x-home.find-my-location />

@if(!auth()->guard('user')->check())
    @include('components.home.user.reset-password')
    {{--Login register modal--}}
    <x-home.user.login-popup/>
    {{--reset password modal--}}

@endif

@section('popup') @show

@include('Home.Layouts.Footer')

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
{{-- <script src="{{ asset('common/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
<script src="{{ asset('common/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('common/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('common/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('common/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('common/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('common/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<script src="{{ asset('common/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<script src="{{ asset('common/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('common/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
{{-- <script src="{{ asset('common/plugins/summernote/summernote-bs4.min.js') }}"></script> --}}
<script src="{{ asset('common/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('common/adminlte/js/adminlte.min.js') }}"></script>
{{-- end admin lte3 --}}

<script src="{{asset('user/vendor/jquery-ui-1.13.2/jquery-ui.min.js')}}"></script>

<!-- jQuery -->
{{-- <script src="{{asset('frontend/plugins/jquery/jquery.min.js')}}"></script> --}}
<!-- jQuery UI 1.11.4 -->
{{-- <script src="{{asset('frontend/plugins/jquery-ui/jquery-ui.min.js')}}"></script> --}}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!--Date Calendar-->
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
<!-- Bootstrap 4 -->
{{-- <script src="{{asset('frontend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
<script src="{{asset('frontend/js/rating.js')}}"></script>
<!-- <script src="https://cdn.rawgit.com/matthieua/WOW/1.0.1/dist/wow.min.js"></script> -->
<!-- LightBox -->
<script src="{{asset('frontend/plugins/html5lightbox/froogaloop2.min.js')}}"></script>
<script src="{{asset('frontend/plugins/html5lightbox/html5lightbox.js')}}"></script>
{{-- <script src="{{asset('frontend/plugins/ekko-lightbox/ekko-lightbox.js')}}"></script> --}}
{{-- <!-- <script src="{{asset('frontend/plugins/chart.js/Chart.min.js')}}"></script> --> --}}
{{-- <script src="{{asset('frontend/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script> --}}
<script src="{{asset('frontend/plugins/sticky-sidebar-master/dist/sticky-sidebar.js')}}"></script>
<script src="{{asset('frontend/plugins/css-element-queries-master/tests/src/ElementQueries.js')}}"></script>
{{-- <script src="{{asset('frontend/js/owl.carousel.min.js')}}"></script> --}}

<!-- lazy load img -->
<script src="{{ asset('common/plugins/jquery.lazy.1.7.9/jquery.lazy.min.js') }}"></script>
<script src="{{ asset('common/plugins/jquery.lazy.1.7.9/jquery.lazy.plugins.min.js') }}"></script>

{{-- app page owl carousel --}}
<script src="{{ asset('user/vendor/owl-carousel-2.3.4/owl.carousel.min.js') }}"></script>
<script src="{{ asset('user/vendor/page-owl-carousel.js') }}"></script>

{{-- tiny mce --}}
<script src="{{ asset('user/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('system/js/tinymce4x/vi_VN.js') }}"></script>

<script src="{{ asset('common/plugins/fancybox/jquery.fancybox.js') }}"></script>

<script src="{{asset('frontend/js/slick.js')}}"></script>

{{-- plugin init all fancybox,tinimce --}}
<script src="{{ asset('common/js/page-plugin-init.js') }}"></script>

<script src="{{asset('frontend/js/app/app.js')}}"></script>
<script src="{{asset('frontend/js/global/search-classified.js')}}"></script>
<script src="{{asset('frontend/js/main.js')}}"></script>
<script src="{{asset('frontend/js/plus1.js')}}"></script>
<script src="{{asset('frontend/js/plus2.js')}}"></script>
<script src="{{asset('frontend/js/plusb.js')}}"></script>
<script src="{{asset('frontend/js/plushao.js')}}"></script>
{{-- classified search --}}
<script src="{{asset('frontend/dist/js/adminlte.min.js')}}"></script>

<!-- SweetAlert -->
{{-- <script src="{{asset('system/js/sweetalert2@11/sweetalert2@11.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('frontend/js/custom.js')}}"></script>
<script type="text/javascript" src="{{asset('frontend/js/login.js')}}"></script>
<script type="text/javascript" src="{{asset('frontend/js/app/main.js')}}"></script>
<script type="text/javascript" src="{{asset('frontend/js/global/main.js')}}"></script>

<script src="{{asset('user/js/classified.js')}}"></script>

{{-- google map --}}
<script src="https://maps.googleapis.com/maps/api/js?key={!!$google_api_key!!}&callback=callback&libraries=places&v=weekly" defer></script>
<script type="text/javascript" src="{{ asset('common/js/new-map.js') }}"></script>
<script>
    function callback() {
        @stack('init_map')
    }
</script>
{{-- google map --}}

{!! Toastr::message() !!}

<script>
    // should check and improve
    {{--open_current_popup("{{Session::get('popup')}}")--}}
    open_display_current_popup("{{Session::get('popup_display')}}", "{{Session::get('popup_action')}}")
    {{--scroll_to_element("{{Session::get('scroll_to')}}")--}}
</script>

@section('Script')
@show
@yield('script')
@stack('scripts_children')
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
