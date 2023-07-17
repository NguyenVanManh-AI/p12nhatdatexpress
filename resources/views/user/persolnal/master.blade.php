<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Trang cá nhân | Nhà đất express')</title>
    <link rel="icon" type="image/x-icon" href="{{asset(SystemConfig::logo())}}">
    <meta name="description" content="@yield('description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta name="keywords" content="@yield('Keywords','Nhà đất express')">
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="@yield('title','Trang cá nhân | Nhà đất express')">
    <meta itemprop="description" content="@yield('description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta itemprop="image" content="@yield('image',asset(SystemConfig::logo()))">
    <meta itemprop="url" content="@yield('url',request()->fullUrl())">

    <!-- Facebook Meta Tags -->
    <meta property="og:url"         content="@yield('url', request()->fullUrl())">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="@yield('title', data_get($home_seo_config, 'meta_title', 'Trang cá nhân | Nhà đất express'))">
    <meta property="og:description" content="@yield('description', data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))">
    <meta property="og:image"       content="@yield('image', $home_seo_config ? $home_seo_config->getSeoBanner() : asset(SystemConfig::logo()))">
    <!-- END facebook SEO-->

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="{{ asset('fonts/_source-sans-pro-font.css') }}"> --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ion-rangeslider -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css')}}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('frontend/dist/css/adminlte.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/summernote/summernote-bs4.min.css')}}">
    <!-- LightBox -->
    <link rel="stylesheet" href="{{asset('frontend/plugins/ekko-lightbox/ekko-lightbox.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/owl.carousel.css')}}">
    <!--Date Calendar-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="{{asset('frontend/css/variables.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('system/css/toastr/toastr.min.css')}}" >
    <link rel="stylesheet" href="{{asset('frontend/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/global-custom.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/main/page.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/personal/page.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/main/header-footer.css')}}">

    <style>
        input,textarea{
            outline: none;
            border: none;
        }
        select{
            outline: none;
        }
        textarea:focus{
            border: none;
        }
    </style>
    @section('Style')
    @show
</head>

<body class="sidebar-mini layout-fixed page-profile personal-profile-page" cz-shortcut-listen="true" style="height: auto;">
    @include('Home.Layouts.Header')

@section('content') @show
<x-home.report-modal></x-home.report-modal>

@include('Home.Layouts.Footer')

<div id="layout"></div>
<div class="overlay"></div>

<x-home.classified.popup-select />

@if(!auth()->guard('user')->check())
    @include('components.home.user.reset-password')
    <x-home.user.login-popup/>
@endif

<script src="{{asset('frontend/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('common/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{asset('frontend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!--Date Calendar-->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Bootstrap 4 -->

<script src="{{asset('frontend/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

<!-- LightBox -->
<script src="{{asset('frontend/plugins/html5lightbox/froogaloop2.min.js')}}"></script>
<script src="{{asset('frontend/plugins/html5lightbox/html5lightbox.js')}}"></script>
<script src="{{asset('frontend/plugins/ekko-lightbox/ekko-lightbox.js')}}"></script>
<script src="{{asset('frontend/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('frontend/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('frontend/plugins/sticky-sidebar-master/dist/sticky-sidebar.js')}}"></script>
<script src="{{asset('frontend/plugins/css-element-queries-master/tests/src/ElementQueries.js')}}"></script>
<script src="{{asset('frontend/js/owl.carousel.js')}}"></script>
<script src="{{asset('frontend/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('frontend/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/js/slick.js')}}"></script>
<script src="{{asset('frontend/js/main.js')}}"></script>
<script src="{{asset('frontend/js/plus1.js')}}"></script>
<script src="{{asset('frontend/js/plus2.js')}}"></script>
<script src="{{asset('frontend/js/plusb.js')}}"></script>
<script src="{{asset('frontend/js/plushao.js')}}"></script>
<script src="{{asset('frontend/dist/js/adminlte.min.js')}}"></script>
<!-- lazy load img -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<!-- Toastr -->
<script src="{{asset('system/js/toastr/toastr.min.js')}}"></script>
<!-- SweetAlert -->
<script src="{{asset('system/js/sweetalert2@11/sweetalert2@11.js')}}"></script>
<script type="text/javascript" src="{{asset('frontend/js/custom.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('system/js/main.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('frontend/js/login.js')}}"></script>
<script type="text/javascript" src="{{asset('frontend/js/custom-comment.js')}}"></script>
<script type="text/javascript" src="{{ asset('frontend/js/personal/main.js') }}"></script>
<script type="text/javascript" src="{{asset('frontend/js/global/main.js')}}"></script>

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

<script>

    function change_banner(type_banner,input,add=null,choose=null){
        var image = $(input).prop('files')[0];
        let form = new FormData();
        form.append(type_banner,image);
        form.append('_token','{{csrf_token()}}');
            // document.getElementById('banner_image_left').files;
        $.ajax({
            type:'post',
            url:'{{route('trang-ca-nhan.uploads')}}',
            data:form,
            processData: false,
            cache:false,
            contentType: false,
            success:function (data){
                toastr.success(data);
                    if(add !=null){
                        readURL(input, $(input).parents(".banner-persolnal").find("img.img-ads"));
                    }
                    else{
                        let fileList = $(input).prop('files')[0],
                            $this = $(input);
                        var reader = new FileReader();
                        $this.parents(add).removeClass("not-banner-persolnal");
                        reader.onload = function(e) {
                            $this.parents(add).append(`
                            <a href="#">
                                <img class="img-ads" src="${e.target.result}" alt="">
                            </a>
                            <div class="edit-ads">
                                <i class="far fa-edit"></i>
                                <input id="`+choose+`" type="file">
                            </div>
                        `);
                            $this.parent('.upload-ads').remove();
                        };
                        reader.readAsDataURL(fileList);
                        window.location.reload();
                    }


                // window.location.reload();
            },
            error:function (error){
                // console.log(error);
                toastr.error(error.responseJSON);
            }
        });
    }
    // cập nhật avatar
    function readURLcus(input, name) {
        let file = document.getElementById(input);
        if (file.files && file.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {

                $(name).attr("src", e.target.result);

            };

            reader.readAsDataURL(file.files[0]);

        }

    }
    // change avatar
    $('#input_avatar').on('change',function (){
        var image = $(this).prop('files')[0];
        let form = new FormData();
        form.append('avatar',image);
        form.append('_token','{{csrf_token()}}');
        // document.getElementById('banner_image_left').files;
        $.ajax({
            type:'post',
            url:'{{route('trang-ca-nhan.uploads-avatar')}}',
            data:form,
            processData: false,
            cache:false,
            contentType: false,
            success:function (data){
                toastr.success(data);

                readURLcus('input_avatar','#preview_avatar');

                // window.location.reload();
            },
            error:function (error){
                toastr.error(error.responseJSON);
            }
        });
    });
    // theo dõi người khác
    $('.follow-user').click(function (){
        var id = $(this).data('id');
        $.ajax({
            type:'GET',
            url:'{{route('trang-ca-nhan.follows','')}}'+'/'+id,
            success:function(data){
                toastr.success(data['success']);
                if(data['status']==1){
                    $('.follow-user .name').text('Đang theo dõi');
                }else if(data['status']==0){
                    $('.follow-user .name').text('Theo dõi');
                }
            },
            error:function (error){
               toastr.error(error.responseJSON);
            }
        });
    });
</script>
<script>
    $('.report-persolnal').click(function(){
        $('#report_persolnal').show();
        $('#layout').show();
        $('#report_persolnal').find('form').attr('action','{{route('trang-ca-nhan.report-persolnal','')}}'+'/'+$(this).data('id'));
        $("body").on("click", "#layout", function (event) {
            $('#report_persolnal').hide();
        });
    });
    $('.report-content').click(function(){
        $('#report_content').show();
        $('#layout').show();
        $('#report_content').find('form').attr('action','{{route('trang-ca-nhan.report-content','')}}'+'/'+$(this).data('post_id'));
        $("body").on("click", "#layout", function (event) {
            $('#report_content').hide();
        });
    });
    $('body').on('click','.button-report .report_comment',function(){
        $('#report_comment').show();
        $('#layout').show();
        $('#report_comment').find('form').attr('action','{{route('trang-ca-nhan.report-comment','')}}'+'/'+$(this).data('comment_id'));
    });
</script>
@section('script')
@show
{{-- <iframe id="redeviation-bs-sidebar" class="notranslate" aria-hidden="true" data-theme="default" data-pos="right" style="opacity: 1; overflow: visible;"></iframe><div id="redeviation-bs-indicator" data-theme="default" class="redeviation-bs-fullHeight" style="height: 100%; top: 0%;"></div></body>  --}}
</html>
