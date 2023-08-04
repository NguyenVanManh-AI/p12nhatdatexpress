<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('Title')</title>
    <base href="{{asset('system')}}/" />
    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="{{ asset('fonts/_source-sans-pro-font.css') }}"> --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
    <link rel="stylesheet" href="css/ionicons/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

    <link rel="stylesheet" href="{{ asset('common/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <link rel="stylesheet" href="{{ asset('common/plugins/fancybox/jquery.fancybox.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="css/toastr/toastr.min.css">
    <!-- Main style -->
    <link rel="stylesheet" href="{{asset('frontend/css/global-custom.css')}}">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="{{ asset('frontend/css/variables.css') }}">

    <link rel="stylesheet" href="{{ mix("css/chat-box.css") }}" as="style">

    @section('Style')
    @show
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="overlay"></div>
<!-- .wrapper -->
<div class="wrapper">

    {{-- <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div> --}}

@include('Admin.Layouts.Header')
@include('Admin.Layouts.Sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @section('Content')
        @show

        {{-- should remove base tag above <base href="{{asset('system')}}/" /> --}}
        {{-- <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a> --}}
    </div>
    <!-- /.content-wrapper -->
@include('Admin.Layouts.Footer')
    <aside class="control-sidebar control-sidebar-dark">
        Control sidebar content goes here
    </aside>
</div>

{{-- chat popup --}}
@if(auth('admin')->user()->canChat())
    <div id="chat-boxes" class="support__chat-boxes border hover-shadow rounded {{ session('opened-conversation') ? 'opened minimum' : '' }}"
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

<!-- ./wrapper -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ asset('common/plugins/fancybox/jquery.fancybox.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<script src="{{ asset('common/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('common/plugins/select2/js/i18n/vi.js') }}"></script>

<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- SweetAlert -->
<script src="js/sweetalert2@11/sweetalert2@11.js"></script>
<!-- Toastr -->
<script src="js/toastr/toastr.min.js"></script>

{!! Toastr::message() !!}

<!-- INIT ECHO -->
<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.js"></script>

<script src="{{ asset('user/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('system/js/tinymce4x/vi_VN.js') }}"></script>

<script src="{{asset('frontend/js/slick.js')}}"></script>
{{-- plugin init all fancybox,tinimce --}}
<script src="{{ asset('common/js/page-plugin-init.js') }}"></script>

<!-- <script>
    $(function (){
        const echo = new Echo({
            broadcaster: "socket.io",
            host: window.location.hostname + ':6001'
        })

        echo.join('online')
            .here((users) => {
                console.log(users)
            })
            .joining((user) => {
                console.log('joining', user)
            })
            .leaving((user) => {
                console.log('logout', user)
            });
    })
</script> -->
<!-- INIT ECHO -->

<!-- Main -->
<script src="js/main.js"></script>
<script src="{{asset('frontend/js/app/app.js')}}"></script>
<script src="{{ asset('frontend/js/global/main.js') }}"></script>
{{-- <script src="{{ asset('frontend/js/global/admin/common.js') }}"></script> --}}
@section('Script')
@show
@stack('scripts')

@if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
    <x-admin.popup.create-notify />
@endif

    @if(auth('admin')->user()->canChat())
        {{-- chat-box --}}
        <script type="text/javascript">
            const SOCKET_HOST_URL = window.location.hostname + '{{ config('app.env') == 'local' ? ':6001' : '' }}'
            const USER_CHANNEL_TOKEN = "{{ auth('admin')->user()->channel_token ?? '' }}"
            const ACTIVE_ONVERSATION_TOKEN = '';
        </script>
        <script src="{{ mix("js/chat-box.js") }}"></script>
    @endif
</body>
</html>
