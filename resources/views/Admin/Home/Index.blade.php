@extends('Admin.Layouts.Master')
@section('Title', 'Cấu hình trang chủ')
@section('Content')
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <form action="{{route('admin.home.saved')}}" method="post" enctype="multipart/form-data"
                  class="form form-setting-home">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="box image-first-desktop">
                            <h3 class="box-header">Ảnh đầu trang trên Desktop</h3>
                            <div class="inner">
                                <div class="choose-image">
                                    <img src="image/upload-file.png"
                                         style="height: 40px;max-width: 100%;max-height: 100%" alt="" id="imglogo1">
                                    <span class="desc d-block">Kéo &amp; Thả ảnh tại đây!</span>
                                    <span class="btn btn-upload">Tải ảnh lên</span>
                                    <!-- <input type="file"> -->
                                    <input type="file" accept="image/*" class="desktop_header_image"
                                           name="desktop_header_image" onchange="showMyImageDesktop(this)"/>
                                    <br/>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('desktop_header_image'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('desktop_header_image')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <div class="box image-first-mobile">
                            <h3 class="box-header">Ảnh đầu trang trên Mobile</h3>
                            <div class="inner">
                                <div class="choose-image">
                                    <img src="image/upload-file.png"
                                         style="height: 40px;max-width: 100%;max-height: 100%" alt="" id="imglogo2">
                                    <span class="desc d-block">Kéo &amp; Thả ảnh tại đây!</span>
                                    <span class="btn btn-upload">Tải ảnh lên</span>
                                    <!-- <input type="file"> -->
                                    <input type="file" accept="image/*" class="mobile_header_image"
                                           name="mobile_header_image" onchange="showMyImageMobile(this)"/>
                                    <br/>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('mobile_header_image'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('mobile_header_image')}}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="form-row mt-1">
                    <div class="form-group col-md-6">
                        <div id="image-first-desktop" class="contain-photo">
                            <img id="thumbnil-desktop"
                                 src="{{(isset($home_config->desktop_header_image) && ($home_config->desktop_header_image!=null))?url('system/img/home-config/'.$home_config->desktop_header_image):""}}"
                                 alt="">
                            <a class="delete_thumbnil_desktop del-image" style="cursor: pointer;display: none">Xóa</a>
                            @if($check_role ==1 || key_exists(7, $check_role))
                                @if(isset($home_config->desktop_header_image))
                                    <a data-id="{{$home_config->id}}" data-type="desktop_header_image"
                                       data-image="{{$home_config->desktop_header_image}}"
                                       data-created_at="{{\Crypt::encryptString($home_config->created_at)}}"
                                       class="del-image delete_image delete_thumbnil_desktop_old"
                                       style="cursor: pointer">Xóa</a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div id="image-first-mobile" class="contain-photo">
                            <img id="thumbnil-mobile"
                                 src="{{(isset($home_config->mobile_header_image) && ($home_config->mobile_header_image!=null))?url('system/img/home-config/'.$home_config->mobile_header_image):""}}"
                                 alt="">
                            <a class="delete_thumbnil_mobile del-image" style="cursor: pointer;display: none">Xóa</a>
                            @if($check_role ==1 || key_exists(7, $check_role))
                                @if(isset($home_config->mobile_header_image))
                                    <a
                                        data-id="{{$home_config->id}}"
                                        data-type="mobile_header_image"
                                        data-image="{{$home_config->mobile_header_image}}"
                                        data-created_at="{{\Crypt::encryptString($home_config->created_at)}}"
                                        class="del-image delete_image delete_thumbnil_mobile_old"
                                        style="cursor: pointer">Xóa</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label for="">Số lượng tin rao mục <span class="text-orange">xem nhiều</span></label>
                        <input type="text" name="num_most_viewed" class="form-control"
                               value="@if(old('num_most_viewed')!=""){{old('num_most_viewed')}}@elseif(isset($home_config->num_most_viewed)){{$home_config->num_most_viewed}}@endif">
                        @if($errors->has('num_most_viewed'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('num_most_viewed')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Số lượng tin rao tại trang chủ</label>
                        <input type="text" name="num_classified" class="form-control"
                               value="@if(old('num_classified')!=""){{old('num_classified')}}@elseif(isset($home_config->num_classified)){{$home_config->num_classified}}@endif">
                        @if($errors->has('num_classified'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('num_classified')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Khối văn bản đầu trang</label>
                        <input type="text" name="header_text_block"  class="form-control header_text_block"
                               value="@if(old('header_text_block')!=""){{old('header_text_block')}}@elseif(isset($home_config->header_text_block)){{$home_config->header_text_block}}@endif">
                        @if($errors->has('header_text_block'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('header_text_block')}}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="block-dashed mt-4">
                    <h3 class="title">Thiết lập popup trang chủ trên máy tính</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="box">
                                <div class="inner">
                                    <div class="choose-image">
                                        <img src="./images/upload-file.png" alt="">
                                        <img src="image/upload-file.png"
                                             style="height: 40px;max-width: 100%;max-height: 100%" alt="" id="imglogo3">
                                        <span class="desc d-block">Kéo &amp; Thả ảnh tại đây!</span>
                                        <span class="btn btn-upload">Tải ảnh lên</span>
                                        <!-- <input type="file"> -->
                                        <input type="file" accept="image/*" class="popup_image" name="popup_image"
                                               onchange="showMyImagePopup(this)"/>
                                        <br>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div id="poppup-home-desktop" class="contain-photo">
                                <img id="thumbnil-popup"
                                     src="{{(isset($home_config->popup_image) && ($home_config->popup_image!=null))?url('system/img/home-config/'.$home_config->popup_image):""}}"
                                     alt="">
                                <a class="delete_thumbnil_popup del-image" style="cursor: pointer;display: none">Xóa</a>
                                @if($check_role ==1 || key_exists(7, $check_role))
                                    @if(isset($home_config->popup_image))
                                        <a data-id="{{$home_config->id}}" data-type="popup_image"
                                           data-image="{{$home_config->popup_image}}"
                                           data-created_at="{{\Crypt::encryptString($home_config->created_at)}}"
                                           class="del-image delete_image delete_thumbnil_popup_old"
                                           style="cursor: pointer">Xóa</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($errors->has('popup_image'))
                        <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                            {{$errors->first('popup_image')}}
                        </small>
                    @endif
                </div>

                <div class="text-center mb-3">
                    <label class="switch">
                        <input type="checkbox"
                               {{ (isset($home_config->popup_status) && $home_config->popup_status ==1  )?"checked":""  }}  name="popup_status"
                               id="togBtn">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="form-row">
                    <div class="form-group offset-md-4 col-md-4">
                        <label for="" class="d-block text-center">Thời gian lặp lại</label>
                        <select class="custom-select" name="popup_time">
                            <option
                                {{ (isset($home_config->popup_time)&& $home_config->popup_time ==0  )?"selected":"" }} value="0">
                                1 ngày
                            </option>
                            <option
                                {{ (isset($home_config->popup_time)&& $home_config->popup_time ==1  )?"selected":""  }} value="1">
                                1 tuần
                            </option>
                            <option
                                {{ (isset($home_config->popup_time)&& $home_config->popup_time ==2  )?"selected":""  }} value="2">
                                10 ngày
                            </option>
                            <option
                                {{ (isset($home_config->popup_time)&& $home_config->popup_time ==3  )?"selected":""  }} value="3">
                                1 tháng
                            </option>
                        </select>
                        @if($errors->has('popup_time'))
                            <small id="passwordHelp" style="font-size: 100%" class="text-danger">
                                {{$errors->first('popup_time')}}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="form-row justify-content-center text-center">
                    <div class="form-group col-md-4">
                        <label for="">Đường dẫn popup</label>
                        <input type="text" name="popup_link"  class="form-control header_text_block"
                               value="@if(old('popup_link')!=""){{old('popup_link')}}@elseif(isset($home_config->popup_link)){{$home_config->popup_link}}@endif">
                        @if($errors->has('popup_link'))
                            <small style="font-size: 100%" class="text-danger">
                                {{$errors->first('popup_link')}}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="text-center">
                    @if($check_role ==1 || key_exists(2, $check_role))
                        <input type="submit" class="btn btn-save mb-3" value="Lưu">
                    @endif
                </div>
            </form>
        </div>
    </section>
@endsection
@section('Style')
    <style>
        .image-first-desktop .box-header {
            background-color: #0054a6;
        }
        .box > .box-header {
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            color: #fff;
            line-height: 18px;
            padding: 10px;
            background-color: #333;
        }
        .image-first-mobile .box-header {
            background-color: #f26c4f;
        }
        .box > .inner {
            padding: 12px;
            border: 1px solid #b7b7b7;
        }
        .form-setting-home .choose-image {
            height: 122px;
        }
        .form-setting-home .choose-image {
            border-style: dashed;
        }
        .form .choose-image {
            border: 1px solid #d7d7d7;
            height: 114px;
            text-align: center;
            position: relative;
        }
        .form .choose-image .desc {
            color: #64bbf0;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .form .choose-image .btn-upload {
            border-radius: 3px;
            background-color: #00a8ec;
            color: #fff;
            padding: 6px 8px;
            margin-top: 5px;
            line-height: 1;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .form .choose-image input[type="file"] {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            opacity: 0;
            cursor: pointer;
        }
        .image-first-desktop .box-header {
            background-color: #0054a6;
        }
        .image-first-mobile .box-header {
            background-color: #f26c4f;
        }
        .box > .box-header {
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            color: #fff;
            line-height: 18px;
            padding: 10px;
            /*background-color: #333;*/
        }
        .contain-photo {
            padding: 10px;
            border: 1px solid #b7b7b7;
            height: 212px;
            position: relative;
            text-align: center;
        }
        .contain-photo img {
            max-width: 100%;
            max-height: 100%;
        }
        .contain-photo .del-image {
            display: inline-block;
            position: absolute;
            bottom: 10px;
            right: 10px;
            text-decoration: underline;
            color: #ff0000;
            font-size: 1rem;
        }
        #poppup-home-desktop.contain-photo {
            height: 140px;
        }
        .contain-photo {
            padding: 10px;
            border: 1px solid #b7b7b7;
            height: 212px;
            position: relative;
            text-align: center;
        }
        label:not(.form-check-label):not(.custom-file-label) {
            font-weight: 700;
        }
        label:not(.form-check-label):not(.custom-file-label) {
            font-weight: 700;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 90px;
            height: 34px;
        }
        .switch input {
            display: none;
        }
        input[type=checkbox],
        input[type=radio] {
            box-sizing: border-box;
            padding: 0;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 90px;
            height: 34px;
        }
        .switch input {
            display: none;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #2ab934;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            right: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #ca2222;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(-26px);
            -ms-transform: translateX(-26px);
            transform: translateX(-55px);
        }
        .slider:after {
            content: 'ON';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
        }
        input:checked + .slider:after {
            content: 'OFF';
        }
        .form .btn-save {
            background-color: #00aeef;
        }
        .form .btn-save,
        .form .btn-test {
            width: 111px;
            height: 30px;
            text-align: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            line-height: 1;
            border-radius: unset;
        }
        .form-setting-home .block-dashed {
            padding-left: 12px;
            padding-right: 12px;
        }
        .block-dashed {
            margin: 30px 0;
            padding: 30px 30px 14px;
            position: relative;
            border: 1px dashed #c2c5d6;
        }
        .block-dashed .title {
            display: inline-block;
            font-size: 18px;
            padding: 0 20px;
            background-color: #fff;
            font-weight: 500;
            line-height: 1;
            margin-bottom: 0;
            text-transform: uppercase;
            color: #000;
            position: absolute;
            top: -9px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
    </style>
@endsection
@section('Script')
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
        // $(document).ready(function (){
        //     let text  = $('.header_text_block').val();
        //     let start = text.indexOf('%');
        //     let end = text.indexOf('+');
        //     char =text.substring(start,end+1);
        //     text = text.replace(char,'<span class="text-blue">'+char+'</span>')
        //     $('.header_text_block').val(text);
        //
        // });
    </script>
    <script>
        function showMyImageDesktop(fileInput) {
            $('#thumbnil-desktop').show();
            $('.delete_thumbnil_desktop_old').hide();
            $('.delete_thumbnil_desktop').show();
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    continue;
                }
                var img = document.getElementById("thumbnil-desktop");
                img.file = file;
                var reader = new FileReader();
                reader.onload = (function (aImg) {
                    return function (e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);

            }

        }

        function showMyImageMobile(fileInput) {
            $('#thumbnil-mobile').show();
            $('.delete_thumbnil_mobile_old').hide();
            $('.delete_thumbnil_mobile').show();
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    continue;
                }
                var img = document.getElementById("thumbnil-mobile");
                img.file = file;
                var reader = new FileReader();
                reader.onload = (function (aImg) {
                    return function (e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);
            }
        }

        //desktop
        $('.delete_thumbnil_desktop').click(function () {
            $('.desktop_header_image').val("");
            let mobile = "{{$home_config->desktop_header_image}}";
            if (mobile != "") {
                {{--    $('#thumbnil-mobile').show();--}}
                $('#thumbnil-desktop').attr('src', "{{url('system/img/home-config/'.$home_config->desktop_header_image)}}");
                $('.delete_thumbnil_desktop_old').show();
                $('.delete_thumbnil_desktop').hide();
            } else {
                $('#thumbnil-desktop').hide();
                $('.delete_thumbnil_desktop').hide();
            }
        });
        $('.delete_thumbnil_mobile').click(function () {
            $('.mobile_header_image').val("");
            let mobile = "{{$home_config->mobile_header_image}}";
            if (mobile != "") {
                {{--    $('#thumbnil-mobile').show();--}}
                $('#thumbnil-mobile').attr('src', "{{url('system/img/home-config/'.$home_config->mobile_header_image)}}");
                $('.delete_thumbnil_mobile_old').show();
                $('.delete_thumbnil_mobile').hide();
            } else {
                $('#thumbnil-mobile').hide();
                $('.delete_thumbnil_mobile').hide();
            }
        });
        $('.delete_thumbnil_popup').click(function () {
            $('.popup_image').val("");
            let mobile = "{{$home_config->popup_image}}";
            if (mobile != "") {
                {{--    $('#thumbnil-mobile').show();--}}
                $('#thumbnil-popup').attr('src', "{{url('system/img/home-config/'.$home_config->popup_image)}}");
                $('.delete_thumbnil_popup_old').show();
                $('.delete_thumbnil_popup').hide();
            } else {
                $('#thumbnil-popup').hide();
                $('.delete_thumbnil_popup').hide();
            }
        });

        function showMyImagePopup(fileInput) {
            $('#thumbnil-popup').show();
            $('.delete_thumbnil_popup_old').hide();
            $('.delete_thumbnil_popup').show();
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    continue;
                }
                var img = document.getElementById("thumbnil-popup");
                img.file = file;
                var reader = new FileReader();
                reader.onload = (function (aImg) {
                    return function (e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script>
        $('.delete_image').click(function () {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let image = $(this).data('image');
            let created_at = $(this).data('created_at');
            Swal.fire({
                title: 'Xác nhận xóa ảnh',
                text: "Sau khi xóa sẽ không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/home/delete-image/" + type + "/" + image + "/" + id + "/" + created_at;
                }
            })
        });
    </script>
@endsection
