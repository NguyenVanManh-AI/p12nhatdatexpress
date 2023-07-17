@extends('Admin.Layouts.Master')
@section('Title', 'Thêm danh mục | Quản lý danh mục')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
    <section>
        <div class="row m-0 px-4 pt-3">
            <h4>Thêm danh mục dự án</h4>
            <ol class="breadcrumb mt-1 align-items-center">
                @if($check_role == 1  || key_exists(4, $check_role))
                    <li class="recye px-2">
                        <a href="{{route('admin.projectcategory.list')}}">
                            <i class="fa fa-th-list mr-1"></i>Danh sách
                        </a>
                    </li>
                @endif
                 @if($check_role == 1  || key_exists(8, $check_role))
                    <li class="phay ml-2">
                        /
                    </li>
                    <li class="recye px-2 ml-1">
                        <a href="{{route('admin.projectcategory.trash')}}">
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
        <form action="{{route('admin.projectcategory.new')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 px-3 pb-3">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Tiêu đề <span class="required"></span></label>
                    <input class="form-control required group_name" type="text" name="group_name"
                           placeholder="Nhập tiêu đề danh mục" value="{{old('group_name')}}"/>
                    @if($errors->has('group_name'))
                        <small class="text-danger"  style="font-size: 100%">
                            {{$errors->first('group_name')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <div class="row">
                        <div class="col-6">
                            <label>Ảnh đại diện</label><br>
                            <div class="d-flex">
                                <div class="button-choose-image text-center " style="width: 75%;border:1px solid #ccc">
                                    <p>Chọn hình ảnh</p>
                                    <input name="image_url" accept="image/*" type='file' id="imgInp"
                                           style="width: 100%">
                                </div>
                            </div>
                            @if($errors->has('image_url'))
                                <small class="text-danger " style="font-size: 100%">
                                    {{$errors->first('image_url')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-6">
                            <img class="ml-3" id="blah" src="#" alt="your image" width="75%" style="display: none"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Đường dẫn tĩnh <span class="required"></span></label>
                    <input class="form-control required group_url" type="text" name="group_url"
                           placeholder="ex: duong-dan-tinh"
                           value="{{old('group_url')}}">
                    @if($errors->has('group_url'))
                        <small class="text-danger " style="font-size: 100%">
                            {{$errors->first('group_url')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Danh mục cha</label>
                    <div class="search-reponsive">
                        <select class="form-control select2" style="width: 100%;height: 34px !important;"
                                name="parent_id">
                            <option selected value="34">Danh mục cha dự án</option>
                            @foreach($group_project as $item)
                                <option {{ (old('parent_id') == $item->id) ?"selected":""}} value="{{$item->id}}">{{$item->group_name}}</option>
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
                    <textarea class="js-admin-tiny-textarea" name="group_description" id="group_description"
                              style="width: 100%;height: 300px">{{old('group_description')}}</textarea>
                              @if($errors->has('group_description'))
                            <small class="text-danger" style="font-size: 100%">
                                {{$errors->first('group_description')}}
                            </small>
                        @endif
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
    {{-- <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
        @if(old('image_url'))
        previewOneImgFromInputText('#image_url', '#add-image', '', '{{old('image_url')}}')
        @endif  @if(old('image_url'))
        previewOneImgFromInputText('#image_url', '#add-image', '', '{{old('image_url')}}')
        @endif
        $('.reset').click(function () {
            var text = tinymce.get("group_description").getContent();
            // alert(text);
            if($('#imgInp').val()=="" && $('.group_name').val()=="" && $('.group_url').val()=="" && text =="" ){
                toastr.error("Vui lòng kiểm tra các trường");
            }
            else {
                $('#blah').hide();
                toastr.error("Kiểm tra các trường");
            }
        });
    </script>
     --}}
    <script>
        $(function () {
            $('.select2').select2({
                language: {
                    "noResults": function () {
                        return "Không tìm thấy";
                    }
                },
            })
            // Reset form
            $('#reset_btn').click(function () {
                $('input[type="text"]').val('').attr('value', '')
                $('#imgInp').val('')
                $('#blah').prop('src', '').hide()
                $('.select2').prop('selectedIndex', 0).trigger('change');
                tinymce.get('group_description').setContent('')
                toastr.success('Kiểm tra các trường');
            })

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
