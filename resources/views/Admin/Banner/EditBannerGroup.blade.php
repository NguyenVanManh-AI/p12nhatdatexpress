@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý trang tĩnh | Trang tĩnh')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1  align-items-center">
            <li class="recye px-2">
                <a href="{{route('admin.banner.locate.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
{{--            @if($check_role == 1  ||key_exists(5, $check_role))--}}
{{--                <li class="phay ml-2">--}}
{{--                    /--}}
{{--                </li>--}}
{{--                <li class="recye px-2 ml-1">--}}
{{--                    <a href="{{route('admin.banner.locate.trash')}}">--}}
{{--                        <i class="far fa-trash-alt mr-1"></i>Thùng rác--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endif--}}
{{--            @if($check_role == 1  ||key_exists(1, $check_role))--}}
{{--                <li class="ml-2 phay">--}}
{{--                    /--}}
{{--                </li>--}}
{{--                <li class="add px-2 ml-1 p-1 check active">--}}
{{--                    <a href="{{route('admin.banner.locate.add')}}">--}}
{{--                        <i class="fa fa-edit mr-1"></i>Thêm--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endif--}}
        </ol>
    </div>
    <!-- ./Breakcum -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-left">
                    <h4 class="m-0 font-weight-bold text-uppercase">Sửa nhóm banner</h4>
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
                    <form method="post" action="{{route('admin.banner.locate.update', [$item->id, \Crypt::encryptString($item->created_by)])}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group select2-custom">
                                            <label for="page_content">Nhóm banner <span class="font-weight-bold text-danger">*</span></label>
                                            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="banner_group" data-placeholder="Nhóm banner">
                                                <option selected="selected" value="" disabled>Nhóm banner</option>
                                                <option @if(old('banner_group') == 'H' || $item->banner_group == 'H') {{ 'selected' }} @endif value="H" >Trang chủ</option>
                                                <option @if(old('banner_group') == 'C' || $item->banner_group == 'C') {{ 'selected' }} @endif value="C" >Trang chuyên mục</option>
                                                <option @if(old('banner_group') == 'O' || $item->banner_group == 'O') {{ 'selected' }} @endif value="O" >Khác</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="page_title">Tên nhóm <span class="font-weight-bold text-danger">*</span></label>
                                            <input type="text" name="banner_group_name" class="form-control" id="banner_group_name" value="{{ (old('banner_group_name')) ?? $item->banner_group_name}}">
                                            @if($errors->has('banner_group_name'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_group_name')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group select2-custom">
                                            <label for="page_content">Loại thiết bị <span class="font-weight-bold text-danger">*</span></label>
                                            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="banner_type" data-placeholder="Loại thiết bị">
                                                <option selected="selected" value="" disabled>Thiết bị</option>
                                                <option @if(old('banner_type') == 'D' || $item->banner_type == 'D') {{ 'selected' }} @endif value="D" >Desktop</option>
                                                <option @if(old('banner_type') == 'M' || $item->banner_type == 'M') {{ 'selected' }} @endif value="M" >Mobile</option>
                                            </select>
                                            @if($errors->has('banner_type'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_type')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group select2-custom">
                                            <label for="page_content">Vị trí <span class="font-weight-bold text-danger">*</span></label>
                                            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="banner_position" data-placeholder="Vị trí">
                                                <option selected="selected" value="" disabled>Vị trí</option>
                                                <option @if(old('banner_position') == 'T' || $item->banner_position == 'T') {{ 'selected' }} @endif value="T" >Trên</option>
                                                <option @if(old('banner_position') == 'B' || $item->banner_position == 'R') {{ 'selected' }} @endif value="B" >Dưới</option>
                                                <option @if(old('banner_position') == 'L' || $item->banner_position == 'L') {{ 'selected' }} @endif value="L" >Trái</option>
                                                <option @if(old('banner_position') == 'R' || $item->banner_position == 'R') {{ 'selected' }} @endif value="R" >Phải</option>
                                                <option @if(old('banner_position') == 'C'|| $item->banner_position == 'C') {{ 'selected' }} @endif value="C" >Giữa</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="banner_name">Tên banner <span class="font-weight-bold text-danger">*</span></label>
                                            <input type="text" name="banner_name" class="form-control" id="banner_name" value="{{ (old('banner_name')) ?? $item->banner_name}}">
                                            @if($errors->has('banner_name'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_name')}}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="page_title">Độ rộng của banner(pixel)<span class="font-weight-bold text-danger">*</span></label>
                                                    <input type="number" min="0" name="banner_width" class="form-control" placeholder="Bỏ trống sẽ mặc định là auto" id="banner_width" value="{{ (old('banner_width')) ?? $item->banner_width}}">
                                                    @if($errors->has('banner_width'))
                                                        <small class="text-danger error-message-custom">
                                                            {{$errors->first('banner_width')}}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="page_title">Chiều cao của banner(pixel) <span class="font-weight-bold text-danger">*</span></label>
                                                    <input type="number" min="0" placeholder="Bỏ trống sẽ mặc định là auto" name="banner_height" class="form-control" id="banner_height" value="{{ (old('banner_height')) ?? $item->banner_height}}">
                                                    @if($errors->has('banner_height'))
                                                        <small class="text-danger error-message-custom">
                                                            {{$errors->first('banner_height')}}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="page_title">Giá(coin)</label>
                                                    <input type="number" min="0" name="banner_coin_price" class="form-control" id="banner_coin_price" value="{{ (old('banner_coin_price')) ?? $item->banner_coin_price}}">
                                                    @if($errors->has('banner_coin_price'))
                                                        <small class="text-danger error-message-custom">
                                                            {{$errors->first('banner_coin_price')}}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="page_title">Giá(VNĐ) <span class="font-weight-bold text-danger">*</span></label>
                                                    <input type="number" min="0" name="banner_price" class="form-control" id="banner_price" value="{{ (old('banner_price')) ?? $item->banner_price}}">
                                                    @if($errors->has('banner_price'))
                                                        <small class="text-danger error-message-custom">
                                                            {{$errors->first('banner_price')}}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-control-409 mr-2">
                                                    <input id="banner_permission" type="checkbox" value="1"  name="banner_permission" {{ (old('banner_permission') == 1 || $item->banner_permission == 1) ? "checked" : ""}} >
                                                </label>
                                                <label for="banner_permission">Cho phép người dùng quảng cáo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="page_content">Mô tả </label>
                                            <textarea class="form-control" name="banner_description" id="banner_description" rows="8">{{ (old('banner_description')) ?? $item->banner_description}}</textarea>
                                            @if($errors->has('banner_description'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('banner_description')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary mr-3 no-radius">Hoàn tất</button>
                                        <button type="button" class="btn bg-secondary no-radius" role="button" id="reset_btn">Làm lại</button>
                                    </div>
                                </div>
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
                    $('input').not('input[name=_token]').val('')
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
                $('.sao-khong-chay-ta').change(function (){
                    $('#gia-tien-block-' + id).empty()
                    for (let i of $(this).find(':selected')){
                        $('#gia-tien-block-' + id).append(html);
                    }
                });

            });
        });


    </script>
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection
