@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý nhóm trang tĩnh | Trang tĩnh')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1 align-items-center">
            <li class="recye px-2">
                <a href="{{route('admin.static.group')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.static.group.trash')}}">
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
                    <h4 class="m-0 font-weight-bold text-uppercase">Thêm nhóm trang tĩnh</h4>
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
                    <form method="post" action="{{route('admin.static.group.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <div class="form-group">
                                    <label for="group_title">Tiêu đề <span class="font-weight-bold text-danger">*</span></label>
                                    <input type="text" name="group_title" class="form-control" id="group_title" value="{{ (old('group_title')) ?? ""}}">
                                    @if($errors->has('group_title'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('group_title')}}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <label for="title">Cấp cha</label>
                                <div class="select2-custom">
                                    <select class="form-control select2 w-100" name="parent_id">
                                        @if(count($parent_group) > 0)
                                            <option selected="selected" value="">Chọn ---</option>
                                        @endif
                                        @foreach($parent_group as $group)
                                            <option value="{{$group->id}}" {{ old('parent_id') == $group->id ? "selected" : ""}}>{{$group->group_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('parent_id'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('parent_id')}}
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <h3>Tối ưu hóa SEO</h3>
                                <i class="text-gray">Xem kết quả tìm kiếm</i>
                                <div class="mt-2 py-2 px-3" style="background-color: #efefef">
                                    <p class="mb-1" style="color: #1a0dab; font-size: 18px; word-wrap: break-word" id="google_title_seo"></p>
                                    <p class="mb-1" style="color: #006621; font-size: 0.9em; word-wrap: break-word">{{\Request::getSchemeAndHttpHost()}}/<span id="google_url_seo"></span></p>
                                    <p class="mb-0" style="color: #545454; word-wrap: break-word" id="google_description_seo"></p>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="group_url">Đường dẫn thân thiện</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <input type="text" name="group_url" class="form-control input-seo" id="group_url" value="{{ (old('group_url')) ?? ""}}">
                                            <p class="text-muted mb-1">Ví dụ: gioi-thieu-ve-chung-toi</p>
                                            @if($errors->has('group_url'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('group_url')}}
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
                                                <label for="meta_keyword">Từ khóa trên công cụ tìm kiếm</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                            <textarea class="form-control input-seo" name="meta_keyword" id="meta_keyword" rows="3">{{ (old('meta_keyword')) ?? ""}}</textarea>
                                            <p class="text-muted mb-1">Ví dụ: giới thiệu, công ty</p>
                                            @if($errors->has('meta_keyword'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('meta_keyword')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="meta_description">Mô tả trên công cụ tìm kiếm</label>
                                                <span>Số ký tự: <b class="character_count">0</b></span>
                                            </div>
                                                <textarea class="form-control input-seo" name="meta_description" id="meta_description" rows="3">{{ (old('meta_description')) ?? ""}}</textarea>
                                            <p class="text-muted mb-1">Không nên nhập quá 200 chữ và cần có từ khóa cần seo</p>
                                            @if($errors->has('meta_description'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('meta_description')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="image_url">Hình ảnh</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend clear-input" style="cursor: pointer">
                                            <label class="input-group-text" for="validatedInputGroupSelect">
                                                <img src="images/icons/icon_clear.png" >
                                            </label>
                                        </div>
                                        <input type="text" class="form-control" id="image_url" name="image_url" value="{{old('image_url') ?? ""}}">
                                        <a class="input-group-addon" href="javascript:{};" type="button" data-toggle="modal" data-target="#modalFile">Chọn hình</a>
                                    </div>
                                    @if($errors->has('image_url'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('image_url')}}
                                        </small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-success mr-3">Hoàn tất</button>
                                        <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />
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
                    $('input[type="text"]').val('').attr('value','')
                    $('.select2').prop('selectedIndex', 0).trigger('change');
                    $('textarea').val('')
                    updateValue();
                    toastr.success('Làm mới thành công');
                })
                // Reset form
                $('.clear-input').click(function () {
                    $(this).parent().find('input').val('')
                })
                $('#group_title').keyup(function (e) {
                    changeToSlug(this,'.input-seo#group_url');
                    $('.input-seo#group_url').trigger('keyup')
                    $('.input-seo#meta_title').val($(this).val()).trigger('keyup')
                    $('.input-seo#meta_keyword').val($(this).val()).trigger('keyup')
                })
                // group_url
                $('.input-seo').keyup(function (e) {
                    $(this).siblings().find('b.character_count').html(e.target.value.length)
                    // group_url
                    if ($(this).attr('id') === 'group_url'){
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
                    else if ($(this).attr('id') === 'meta_description'){
                        $('#google_description_seo').html(e.target.value)
                        $(this).blur(function () {
                            $('#google_description_seo').html($(this).val())
                        })
                    }
                })
                updateValue();
            });
        });
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
