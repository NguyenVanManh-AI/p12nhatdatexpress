@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý trang tĩnh | Trang tĩnh')
@section('Style')
    <style>
        /* checkbox */
        .form-control-409 {
            --form-control-color: #004680;
            font-size: 1.7rem;
        }
        .form-control-409 input {
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 0.8em;
            height: 0.8em;
            border: 2px solid #c3c3c6;
            border-radius: 0;
            transform: translateY(-.075em);
            display: grid;
            place-content: center
        }
        .form-control-409 input::before {
            content: "";
            width: .42em;
            height: .42em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em var(--form-control-color);
        }

        .form-control-409 input:checked::before {
            transform: scale(1)
        }
    </style>
@endsection
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1  align-items-center">
            <li class="recye px-2">
                <a href="{{route('admin.static.page')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.static.page.trash')}}">
                        <i class="far fa-trash-alt mr-1"></i>Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 ml-1 p-1 check active">
                    <a href="javascript:{}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- ./Breakcum -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-left">
                    <h4 class="m-0 font-weight-bold text-uppercase">Thêm trang tĩnh</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="post" action="{{route('admin.static.page.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="page_title">Tiêu đề <span class="font-weight-bold text-danger">*</span></label>
                                    <input type="text" name="page_title" class="form-control" id="page_title" value="{{ (old('page_title')) ?? ""}}">
                                    @if($errors->has('page_title'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('page_title')}}
                                        </small>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="page_description">Mô tả ngắn <span class="font-weight-bold text-danger">*</span></label>
                                        <textarea class="js-admin-tiny-textarea" name="page_description" id="page_description">{{ (old('page_description')) ?? ""}}</textarea>
                                        @if($errors->has('page_description'))
                                            <small class="text-danger error-message-custom">
                                                {{$errors->first('page_description')}}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <label for="page_content">Nội dung <span class="font-weight-bold text-danger">*</span></label>
                                        <textarea class="js-admin-tiny-textarea" name="page_content" id="page_content">{{ (old('page_content')) ?? ""}}</textarea>
                                        @if($errors->has('page_content'))
                                            <small class="text-danger error-message-custom">
                                                {{$errors->first('page_content')}}
                                            </small>
                                        @endif
                                    </div>
                                </div>
{{--                                <div class="row my-4">--}}
{{--                                    <div class="col-12">--}}
{{--                                        <label class="form-control-409 mr-2">--}}
{{--                                            <input id="is_highlight" type="checkbox" value="1"  name="is_highlight" {{ (old('is_highlight')) == 1 ? "checked" : ""}} >--}}
{{--                                        </label>--}}
{{--                                        <label for="is_highlight">Nổi bật</label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h3>Tối ưu hóa SEO</h3>
                                        <i class="text-gray">Xem kết quả tìm kiếm</i>
                                        <div class="mt-2 py-2 px-3" style="background-color: #efefef">
                                            <p class="mb-1" style="color: #1a0dab; font-size: 18px; word-wrap: break-word" id="google_title_seo"></p>
                                            <p class="mb-1" style="color: #006621; font-size: 0.9em; word-wrap: break-word">{{\Request::getSchemeAndHttpHost()}}/<span id="google_url_seo"></span></p>
                                            <p class="mb-0" style="color: #545454; word-wrap: break-word" id="google_description_seo"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="page_url">Đường dẫn thân thiện</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <input type="text" name="page_url" class="form-control input-seo" id="page_url" value="{{ (old('page_url')) ?? ""}}">
                                            <p class="text-muted mb-1">Ví dụ: gioi-thieu-ve-chung-toi</p>
                                            @if($errors->has('page_url'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('page_url')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="meta_title">Tiêu đề của trang</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <input type="text" name="meta_title" class="form-control input-seo" id="meta_title" value="{{ (old('meta_title')) ?? ""}}">
                                            <p class="text-muted mb-1">Ví dụ: giới thiệu | gioi thieu</p>
                                            @if($errors->has('meta_title'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('meta_title')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="meta_key">Từ khóa trên công cụ tìm kiếm</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <textarea class="form-control input-seo" name="meta_key" id="meta_key" rows="5">{{ (old('meta_key')) ?? ""}}</textarea>
                                            <p class="text-muted mb-1">Ví dụ: giới thiệu, công ty</p>
                                            @if($errors->has('meta_key'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('meta_key')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="meta_desc">Mô tả trên công cụ tìm kiếm</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <textarea class="form-control input-seo" name="meta_desc" id="meta_desc" rows="5">{{ (old('meta_desc')) ?? ""}}</textarea>
                                            <p class="text-muted mb-1">Không nên nhập quá 200 chữ và cần có từ khóa cần seo</p>
                                            @if($errors->has('meta_desc'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('meta_desc')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
{{--                            <div class="col-lg-4 col-md-12" style="padding-left: 50px">--}}
{{--                                <div id="right">--}}
{{--                                    <label for="title">Nhóm  <span class="font-weight-bold text-danger">*</span></label>--}}
{{--                                    <div class="select2-custom">--}}
{{--                                        <select class="form-control select2 w-100" name="page_group">--}}
{{--                                            @if(count($parent_group) > 0)--}}
{{--                                                <option selected="selected" value="">Chọn ---</option>--}}
{{--                                            @endif--}}
{{--                                            @foreach($parent_group as $group)--}}
{{--                                                <option value="{{$group->id}}" {{ old('page_group') == $group->id ? "selected" : "" }}>{{$group->group_title}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    @if($errors->has('page_group'))--}}
{{--                                        <small class="text-danger error-message-custom">--}}
{{--                                            {{$errors->first('page_group')}}--}}
{{--                                        </small>--}}
{{--                                    @endif--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="image_url">Hình ảnh</label>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <div class="input-group-prepend clear-input" style="cursor: pointer">--}}
{{--                                                <label class="input-group-text" for="validatedInputGroupSelect">--}}
{{--                                                    <img src="images/icons/icon_clear.png" >--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <input type="text" class="form-control" id="image_url" name="image_url" value="{{old('image_url')}}">--}}
{{--                                            <a class="input-group-addon" href="javascript:{};" type="button" data-toggle="modal" data-target="#modalFile">Chọn hình</a>--}}
{{--                                        </div>--}}
{{--                                        @if($errors->has('image_url'))--}}
{{--                                            <small class="text-danger error-message-custom">--}}
{{--                                                {{$errors->first('image_url')}}--}}
{{--                                            </small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="d-flex justify-content-end mt-4">--}}
{{--                                            <button class="btn btn-success mr-3">Hoàn tất</button>--}}
{{--                                            <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-center my-2">
                                <button class="btn btn-success mr-3">Hoàn tất</button>
                                <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
            <div class="modal-dialog modal-file" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_url}}" frameborder="0"
                                style="width: 100%; height: 70vh"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script>
        jQuery(document).ready(function ($) {
            $(function () {
                // Reset form
                $('#reset_btn').click(function () {
                    $('input[type="text"]').val('').attr('value','')
                    $('input[type="checkbox"]').prop('checked',false)
                    $('.select2').prop('selectedIndex', 0).trigger('change');
                    $('textarea').val('')
                    tinymce.get('page_content').setContent('')
                    tinymce.get('page_description').setContent('')
                    $('#google_url_seo').html("")
                    $('#google_description_seo').html("")
                    $('#google_title_seo').html("")
                    updateValue();
                    toastr.success('Làm mới thành công');
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing');
                })
                // Reset form
                $('.clear-input').click(function () {
                    $(this).parent().find('input').val('')
                })

                $('#page_title').keyup(function (e) {
                    changeToSlug(this,'.input-seo#page_url');
                    $('.input-seo#page_url').trigger('keyup')
                    $('.input-seo#meta_title').val($(this).val()).trigger('keyup')
                    $('.input-seo#meta_key').val($(this).val()).trigger('keyup')
                })
                getTinyContentWithoutHTML('page_description', 'blur', '.input-seo#meta_desc', 200)

                // page_url
                $('.input-seo').keyup(function (e) {
                    $(this).siblings().find('b.character_count').html(e.target.value.length)
                    // page_url
                    if ($(this).attr('id') === 'page_url'){
                        $('#google_url_seo').html(e.target.value)
                        $(this).blur(function () {
                            $('#google_url_seo').html($(this).val())
                        })
                    }
                    // meta_title
                    else if ($(this).attr('id') === 'meta_title'){
                        $('#google_title_seo').html(e.target.value)
                        $(this).blur(function () {
                            $('#google_title_seo').html($(this).val())
                        })
                    }
                    // description
                    else if ($(this).attr('id') === 'meta_desc'){
                        $('#google_description_seo').html(e.target.value)
                        $(this).blur(function () {
                            $('#google_description_seo').html($(this).val())
                        })
                    }
                })
                updateValue();
                createSticky($("#right"));
            });
        });
        function createSticky(sticky) {

            if (typeof sticky !== "undefined") {

                var	pos = sticky.offset().top,
                    win = $(window);

                win.on("scroll", function() {
                    win.scrollTop() >= pos ? sticky.addClass("sticky_in_parent") : sticky.removeClass("sticky_in_parent");
                });
            }
        }
        function updateValue(){
            $('.input-seo').each(function (e) {
                $(this).siblings().find('b.character_count').html($(this).val().length)
                // group_url
                if ($(this).attr('id') === 'group_url'){
                    $('#google_url_seo').html($(this).val())
                    $(this).blur(function () {
                        $('#google_url_seo').html($(this).val())
                    })
                }
                // meta_title
                else if ($(this).attr('id') === 'meta_title'){
                    $('#google_title_seo').html($(this).val())
                    $(this).blur(function () {
                        $('#google_title_seo').html($(this).val())
                    })
                }
                // description
                else if ($(this).attr('id') === 'meta_description'){
                    $('#google_description_seo').html($(this).val())
                    $(this).blur(function () {
                        $('#google_description_seo').html($(this).val())
                    })
                }
            })
        }
    </script>
    <script>
        // toastr.options = {
        //     "preventDuplicates": true
        // }
        @if(count($errors) > 0)
            toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection
