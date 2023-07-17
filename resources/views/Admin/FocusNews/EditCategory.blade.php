@extends('Admin.Layouts.Master')
@section('Title', 'Sửa danh mục | Quản lý danh mục')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
    <section>
        <div class="row m-0 px-4 pt-3">

            <ol class="breadcrumb mt-1">
                <li class="add px-2 pt-1 check">
                    <a href="{{route('admin.focuscategory.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="">
                        Thùng rác
                    </a>
                </li>
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1 check active">
                    <a href="{{route('admin.focuscategory.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            </ol>
        </div>
        <h4 class="text-center text-bold mt-2">SỬA DANH MỤC TIÊU ĐIỂM</h4>
        <form action="{{route('admin.focuscategory.update', [$item->id,\Crypt::encryptString($item->created_by)])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 px-3 pb-3">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Tiêu đề <span class="required"></span></label>
                    <input class="form-control required group_name" type="text" name="group_name"
                           placeholder="Nhập tiêu đề danh mục" value="{{old('group_name') ?? $item->group_name}}"/>
                    @if($errors->has('group_name'))
                        <small class="text-danger error-message-custom">
                            {{$errors->first('group_name')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <div class="row">
                        <div class="col-6">
                            <label>Ảnh đại diện</label><br>
                            <div class="input-group">
                                <div style="position: relative;width: 100%">
                                    <div class="input-group-prepend clear-input"
                                         style="cursor: pointer;height: 38px;position: absolute" onclick="resetValue()">
                                        <label class="input-group-text" for="validatedInputGroupSelect">
                                            <img src="images/icons/icon_clear.png">
                                        </label>
                                    </div>
                                    <div class="input-group-prepend" data-toggle="modal" data-target="#modalFile"
                                         style="cursor: pointer;height: 38px;position: absolute;left: calc(100% - 100px);">
                                        <label class="input-group-text"
                                               style="cursor: pointer;border-radius: 0 !important; ">
                                            <span style="font-weight:300">Chọn hình</span>
                                        </label>
                                    </div>
                                    <input class="form-control pl-5" id="image_url" name="image_url" onchange="previewOneImgFromInputText(this,'#add-image')"
                                           style="width: 100%;border-radius: .25rem">
                                </div>
                            </div>
                            <script type="text/javascript">
                                function resetValue() {
                                    $('#image_url').val("");
                                }
                            </script>
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
                                            <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&multiple_selection=false&type=1&field_id=image_url&fldr={{\Illuminate\Support\Facades\Auth::guard('admin')->id()}}/focus" frameborder="0"
                                                    style="width: 100%; height: 70vh"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('image_url'))
                                <small class="text-danger error-message-custom">
                                    {{$errors->first('image_url')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                            <div class="form-row add-gallery justify-content-center mt-3" id="add-image" style="border:1px solid #ccc">
                            </div>
                        </div>
                        <div class="col-6">
                            <img class="ml-3" id="blah" src="#" alt="your image" width="75%" style="display: none"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Đường dẫn tĩnh <span class="required"></span></label>
                    <input class="form-control group_url required" type="text" name="group_url" placeholder="ex: duong-dan-tinh"
                           value="{{old('group_url') ?? $item->group_url}}">
                    @if($errors->has('group_url'))
                        <small class="text-danger error-message-custom">
                            {{$errors->first('group_url')}}
                        </small>
                    @endif
                </div>
                {{-- </div>--}}
                {{-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">--}}
                {{--   <label>Thứ tự</label>--}}
                {{--   <input class="form-control required" type="" name="">--}}
                {{-- </div>--}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Danh mục cha</label>
                    <div class="search-reponsive">
                        <select class="form-control select2" style="width: 100%;height: 34px !important;"
                                name="parent_id">
                            <option selected="selected" value="">Danh mục cha</option>
                            @foreach($parent_group as $group)
                                <option
                                    value="{{$group->id}}" {{ (old('parent_id') == $group->id || $item->parent_id == $group->id )? "selected" : ""}}>{{$group->group_name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('parent_id'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('parent_id')}}
                            </small>
                        @endif
                        <span class="promotion-warring">Lưu ý: Nếu bạn muốn thêm một danh mục cha, vui lòng để trống mục này</span>
                    </div>
                </div>
                <div class="col-6"></div>
                <div class="col-12 vol-sm-12 col-md-12 col-lg-12 p-2">
                    <label>Mô tả ngắn</label>
                    <textarea class="js-admin-tiny-textarea group_description" name="group_description" id="group_description" style="width: 100%;height: 300px">{{old('group_description') ?? $item->group_description}}</textarea>
                </div>
                <div class="col-6 mt-2">
                    <button class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
                </div>
                <div class="col-6 mt-2">
                    <input type="reset" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection
@section('Script')
    <script>
        $(function () {
            // Reset form
            $('#reset_btn').click(function () {
            {{--    // $('input[type="text"]').val('').attr('value','')--}}
            {{--    $('.group_name').val('{{$item->group_name}}');--}}
            {{--    $('#imgInp').val('')--}}
                $('#blah').prop('src', '').hide()
            {{--    $('.select2').prop('selectedIndex', 0).trigger('change');--}}
            {{--    --}}{{--tinymce.get('group_description').setContent('{{$item->group_description}}')--}}
            {{--    --}}{{--tinymce.get('group_description').getBody().innerHTML ={{$item->group_description}}--}}
                toastr.success('Làm mới thành công');
            })
        })

    </script>
    <script type="text/javascript">
        $('#tieudiem').addClass('active');
        $('#quanlydanhmuc').addClass('active');
        $('#nav-tieudiem').addClass('menu-is-opening menu-open');
    </script>
    <script src="js/table.js"></script>
    <script>
        @if(count($errors) > 0)
            toastr.error("Vui lòng kiểm tra các trường");
        @endif
        @if($item->image_url)
        previewOneImgFromInputText('#image_url', '#add-image', '', '{{$item->image_url}}')
        @endif
        @if(old('image_url'))
        previewOneImgFromInputText('#image_url', '#add-image', '', '{{old('image_url')}}')
        @endif
    </script>
    <script>
        function toSlug(str) {
            // Chuyển hết sang chữ thường
            str = str.toLowerCase();

            // xóa dấu
            str = str
                .normalize('NFD') // chuyển chuỗi sang unicode tổ hợp
                .replace(/[\u0300-\u036f]/g, ''); // xóa các ký tự dấu sau khi tách tổ hợp

            // Thay ký tự đĐ
            str = str.replace(/[đĐ]/g, 'd');

            // Xóa ký tự đặc biệt
            str = str.replace(/([^0-9a-z-\s])/g, '');

            // Xóa khoảng trắng thay bằng ký tự -
            str = str.replace(/(\s+)/g, '-');

            // Xóa ký tự - liên tiếp
            str = str.replace(/-+/g, '-');

            // xóa phần dư - ở đầu & cuối
            str = str.replace(/^-+|-+$/g, '');

            // return
            return str;
        }
        $('.group_name').on('input',function (){
            $('.group_url').val(toSlug($('.group_name').val()));
        });
    </script>
@endsection
