@extends('Admin.Layouts.Master')
@section('Title', 'Thêm banner')
@section('Style')
    <link rel="stylesheet" href="css/admin-project.css">
@endsection
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1  align-items-center">
            <li class="recye px-2">
                <a href="{{route('admin.banner.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.banner.trash')}}">
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
                    <h4 class="m-0 font-weight-bold text-uppercase">Thêm banner</h4>
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
                    <form method="post" action="{{route('admin.banner.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group select2-custom">
                                            <label for="banner_group_id">Nhóm banner <span class="font-weight-bold text-danger">*</span></label>
                                            <select class="form-control select2" id="banner_group_id" style="width: 100%;height: 34px !important;" name="banner_group_id" data-placeholder="Nhóm banner" onchange="get_group_by_banner_group(this, '#group_id', '{{route('admin.banner.get_group', '')}}/' + this.value)">
                                                <option selected="selected" value="" disabled>Nhóm banner</option>
                                                @foreach($banner_group as $bg)
                                                <option @if(old('banner_group_id') == $bg->id) {{ 'selected' }} @endif value="{{$bg->id}}" >{{$bg->banner_name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('banner_group_id'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_group_id')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group select2-custom">
                                            <label for="group_id">Chuyên mục <span class="font-weight-bold text-danger">*</span></label>
                                            <select class="form-control select2" id="group_id" style="width: 100%;height: 34px !important;" name="group_id" data-placeholder="Chuyên mục">
                                                <option selected="selected" value="" disabled>Chuyên mục</option>
                                                @foreach($group as $g)
                                                <option @if(old('group_id') == $g->id) {{ 'selected' }} @endif value="{{$g->id}}" >{{$g->group_name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('group_id'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('group_id')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="link">Liên kết</label>
                                            <input type="text" name="link" class="form-control" id="link" value="{{ (old('link')) ?? ""}}">
                                            @if($errors->has('link'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('link')}}
                                                </small>
                                            @endif
                                            <div class="mt-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="target_type" id="target_type_1" value="0" checked>
                                                    <label class="form-check-label" for="target_type_1">Tab hiện hành</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="target_type" id="target_type_2" value="1" {{old('target_type') == 1 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="target_type_2">Tab mới</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="banner_title">Tiêu đề banner <span class="font-weight-bold text-danger">*</span></label>
                                            <input type="text" name="banner_title" class="form-control" id="banner_title" value="{{ (old('banner_title')) ?? ""}}">
                                            @if($errors->has('banner_title'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_title')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Ngày bắt đầu - Ngày kết thúc:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right timedates" name="date" placeholder="Chọn ngày bắt đầu - Ngày kết thúc" value="{{old('date') ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                                <label>Ảnh</label>
                                <br>
                                <div style="height: 230px;background: white;border: 1px solid #b7b7b7;padding:8px">
                                    <div class="choose-image" data-toggle="modal" data-target="#modalFileDefault" style="cursor: pointer">
                                        <img src="{{ asset("system/images/icons/upload-file.png")}}" class="mt-4">
                                        <p class="mt-2">Bạn chỉ có thể chọn 1 ảnh <br> cho banner.</p>
                                        <span class="btn btn-upload">Tải ảnh lên</span>
                                        <input type="hidden" value="{{old('image_url')}}" id="image_url" name="image_url" onchange="previewOneImgFromInputText(this, '#add-image-default-url')">
                                    </div>
                                </div>
                                @if($errors->has('image_url'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('image_url')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                                <label>&nbsp;</label>
                                <br />
                                <div class="form-row add-gallery justify-content-center" id="add-image-default-url" style="border:1px solid #ccc">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                                <div class="form-group">
                                    <label for="html-code">HTML Code</label>
                                    <textarea class="form-control" name="banner_code" id="html-code" rows="5" style="height: 250px !important;">{{old('banner_code')}}</textarea>
                                </div>
                                @if($errors->has('banner_code'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('banner_code')}}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary mr-3 no-radius">Hoàn tất</button>
                                        <input type="button" class="btn bg-secondary no-radius" id="reset_btn" value="Làm lại" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalFileDefault" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
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
    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            $(function () {
                // Init select2
                // Init Date range picker with time picker
                initDateRangePicker('input.timedates')
                @if(old('image_url'))
                // Load Image
                previewOneImgFromInputText('#image_url', '#add-image-default-url', '', '{{old('image_url')}}')
                @endif
                // Reset form
                $('#reset_btn').click(function () {
                    $('input[type="text"]').val('').attr('value','')
                    $('input[name="image_default_url"]').val('').attr('value','').trigger('change')
                    $('.add-gallery').empty()
                    $('#target_type_2').prop('checked', true)
                    $('input[type="checkbox"]').prop('checked',false)
                    $('.select2').prop('selectedIndex', 0).trigger('change');
                    $('textarea').val('')
                    toastr.success('Làm mới thành công');
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing');
                })
                // Reset form
                $('.clear-input').click(function () {
                    $(this).parent().find('input').val('')
                })

                @if(old('banner_group_id'))
                // Load group
                get_group_by_banner_group('#banner_group_id', '#group_id', '{{route('admin.banner.get_group', '')}}/' + {{old('banner_group_id') ?? null}},
                    {{old('group_id') ?? null}});
                @endif

            });
        });


    </script>
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection
