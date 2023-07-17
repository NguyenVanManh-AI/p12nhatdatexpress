@extends('Admin.Layouts.Master')
@section('Title', 'Thêm danh mục tin rao | Quản lý danh mục')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style>
        #add-image{
            width: 40%;
            height: 150px;
            margin-left: 3px;
        }
    </style>
@endsection
@section('Content')
    <section>
        <div class="row m-0 p-3">
            <ol class="breadcrumb">
                @if($check_role == 1  ||key_exists(4, $check_role))
                    <li class="recye px-2 pt-1">
                        <a href="{{route('admin.groupclassified.list')}}">
                            <i class="fa fa-th-list mr-1"></i>Danh sách
                        </a>
                    </li>
                @endif
                @if($check_role == 1  ||key_exists(8, $check_role))
                    <li class="phay ml-2">
                        /
                    </li>
                    <li class="recye px-2 pt-1 ml-1">
                        <a href="{{route('admin.groupclassified.listtrash')}}">
                            Thùng rác
                        </a>
                    </li>
                @endif
                @if($check_role == 1  ||key_exists(1, $check_role))
                    <li class="ml-2 phay">
                        /
                    </li>
                    <li class="list-box px-2 pt-1 ml-1">
                        <a href="{{route('admin.groupclassified.new')}}">
                            <i class="fa fa-edit mr-1"></i>Thêm
                        </a>
                    </li>
                @endif
            </ol>
        </div>
        <h4 class="mb-2 text-bold text-center">THÊM DANH MỤC TIN RAO</h4>
        <form action="{{route('admin.groupclassified.new')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 px-3 pb-3">
                <div class="col-md-6 col-sm-12 col-12 ">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                                <label>Tiêu đề <span class="required"></span></label>
                                <input class="form-control required group_name" type="text" name="group_name"
                                       placeholder="Nhập tiêu đề danh mục" value="{{old('group_name')}}"/>
                                @if($errors->has('group_name'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('group_name')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                                <label>Đường dẫn tĩnh <span class="required"></span></label>
                                <input class="form-control required group_url" type="text" name="group_url"
                                       placeholder="ex: duong-dan-tinh"
                                       value="{{old('group_url')}}">
                                @if($errors->has('group_url'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('group_url')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                                <label>Danh mục cha</label>
                                <div class="search-reponsive">
                                    <select class="form-control select2" style="width: 100%;height: 34px !important;"
                                            name="parent_id">
                                        <option selected="selected" value="2">Danh mục cha nhà đất bán</option>
                                        @foreach($group_ndb as $item)
                                            <option selected="selected" value="{{$item->id}}">---- {{$item->group_name}}</option>
                                        @endforeach
                                        <option selected="selected" value="10">Danh mục cha nhà đất cho thuê</option>
                                        @foreach($group_ndct as $item)
                                            <option selected="selected" value="{{$item->id}}">---- {{$item->group_name}}</option>
                                        @endforeach
                                        <option selected="selected" value="18">Danh mục cha cần mua - cần thuê</option>
                                        @foreach($group_cmct as $item)
                                            <option selected="selected" value="{{$item->id}}">---- {{$item->group_name}}</option>
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
                        </div>
                </div>
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-12 pt-2">
                            <div style="width: 40%">
                                <label>Ảnh đại diện </label><br>
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

                            </div>
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
                        <div class="col-12">
                            <div class="form-row add-gallery justify-content-center mt-3" id="add-image" style="border:1px solid #ccc">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 vol-sm-12 col-md-12 col-lg-12 p-2">
                    <label>Mô tả ngắn</label>
                    <textarea class="js-admin-tiny-textarea" name="group_description" id="group_description"
                              style="width: 100%;height: 300px">{{old('group_description')}}</textarea>
                </div>
                <div class="col-6 mt-2">
                    <button class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
                </div>
                <div class="col-6 mt-2">
                    <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại"/>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection
@section('Script')
    <script type="text/javascript">
        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                blah.src = URL.createObjectURL(file)
                $('#blah').show();
            }
        }
    </script>
    <script>
        $(function () {
            // Reset form
            $('#reset_btn').click(function () {
                $('input[type="text"]').val('').attr('value', '')
                $('#imgInp').val('')
                $('#blah').prop('src', '').hide()
                $('.select2').prop('selectedIndex', 0).trigger('change');
                tinymce.get('group_description').setContent('')
                toastr.success('Làm mới thành công');
            })
            {{--            @if($item->image_url != null)--}}
            {{--            $('#blah').prop('src', '{{asset('system/img/focus-news').'/'.$item->image_url}}').show();--}}
            {{--            @endif--}}
        });

        /// function slug
        function slugify(string) {
            const a = 'àáäâãåăæąçćčđďèéěėëêęğǵḧìíïîįłḿǹńňñòóöôœøṕŕřßşśšșťțùúüûǘůűūųẃẍÿýźžż·/_,:;'
            const b = 'aaaaaaaaacccddeeeeeeegghiiiiilmnnnnooooooprrsssssttuuuuuuuuuwxyyzzz------'
            const p = new RegExp(a.split('').join('|'), 'g')
            return string.toString().toLowerCase()
                .replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a')
                .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e')
                .replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i')
                .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o')
                .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u')
                .replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y')
                .replace(/đ/gi, 'd')
                .replace(/\s+/g, '-')
                .replace(p, c => b.charAt(a.indexOf(c)))
                .replace(/&/g, '-and-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '')
        }

        $('.group_name').on("input", function (e) {
            $('.group_url').val(slugify($('.group_name').val()));
        });
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
    </script>
@endsection
