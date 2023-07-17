@extends('Admin.Layouts.Master')
@section('Title', 'Thêm bài viêt | Tiêu điểm')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <link rel='stylesheet'
          href='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.default.min.css'>
@endsection
@section('Content')
    <section>
        <div class="row m-0 px-3 pt-3">

            <ol class="breadcrumb mt-1">
                @if($check_role == 1  ||key_exists(4, $check_role))

                    <li class="add px-2 pt-1 check">
                        <a href="{{route('admin.focus.list')}}">
                            <i class="fa fa-th-list mr-1"></i>Danh sách
                        </a>
                    </li>
                @endif
                @if($check_role == 1  ||key_exists(8, $check_role))

                    <li class="phay ml-2" style="padding-top: 2px">
                        /
                    </li>
                    <li class="recye px-2 pt-1 ml-1">
                        <a href="{{route('admin.focus.listtrash')}}">
                            Thùng rác
                        </a>
                    </li>
                @endif
                @if($check_role == 1  ||key_exists(1, $check_role))

                    <li class="ml-2 phay" style="padding-top: 2px">
                        /
                    </li>
                    <li class="list-box px-2 pt-1 ml-1 check">
                        <a href="{{route('admin.focus.new')}}">
                            <i class="fa fa-edit mr-1"></i>Thêm
                        </a>
                    </li>
                @endif
            </ol>
        </div>
        <h4 class="text-bold text-center m-3">THÊM BÀI VIẾT TIÊU ĐIỂM</h4>
        <form action="{{route('admin.focus.new')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 px-2 pb-3">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <div class="row">
                        <div class="col-12 pb-3">
                            <label>Tiêu đề <span class="required"></span></label>
                            <input value="{{old('news_title')}}" id="news_title" class="form-control" type="text" name="news_title">
                            @if($errors->has('news_title'))
                                <small style="font-size: 100%" class="text-danger">
                                    {{$errors->first('news_title')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-12">
                            <label>Video bài đăng</label>
                            <input value="{{old('video_url')}}" class="form-control" type="text" name="video_url"
                                   placeholder="Nhập đường dẫn video youtube">
                            @if($errors->has('video_url'))
                                <small  style="font-size: 100%" class="text-danger">
                                    {{$errors->first('video_url')}}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2" >
                    <label>Tóm tắt <span class="required"></span></label>
                    <textarea class="form-control" name="news_description" style="height: 117px !important">{{old('news_description')}}</textarea>
                    @if($errors->has('news_description'))
                        <small  style="font-size: 100%" class="text-danger">
                            {{$errors->first('news_description')}}
                        </small>
                    @endif
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Danh mục <span class="required"></span></label>
                    <div class="search-reponsive">
                        <select name="group_id" class="form-control select2"
                                style="width: 100%;height: 34px !important;">
                            <option disabled selected>Chọn danh mục</option>
                            @foreach($group as $item)
                                <option
                                    {{(old('group_id')==$item->id)?"selected":""}}
                                    value="{{$item->id}}">{{(isset($item->child))?"---- ".$item->group_name:$item->group_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('group_id'))
                        <small  style="font-size: 100%" class="text-danger">
                            {{$errors->first('group_id')}}
                        </small>
                    @endif
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Thẻ tag</label>
                    <input type="text" id="input-tags" value="{{old('news_tag')}}" name="news_tag">
                    @if($errors->has('news_tag'))
                        <small  style="font-size: 100%" class="text-danger">
                            {{$errors->first('news_tag')}}
                        </small>
                    @endif
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <div class="">
                        <label>Audio</label>
                        <div class="d-flex w-70">
                            <audio class="mr-3" id="audio" controls style="height: 38px;width: 70%">
                                <source src="" id="src"/>
                            </audio>
                            <div class="button-choose-image text-center" style="width: 30%;border:1px solid #ccc">
                                <p>Chọn file</p>
                                <input type="file" name="audio_url" accept="audio/*" id="upload" style="width: 100%">
                            </div>
                        </div>
                        @if($errors->has('audio_url'))
                            <small  style="font-size: 100%" class="text-danger">
                                {{$errors->first('audio_url')}}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <label>Ảnh đại diện <span class="required"></span></label><br>
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
                            @if($errors->has('image_url'))
                                <small style="font-size: 100%" class="text-danger">
                                    {{$errors->first('image_url')}}
                                </small>
                            @endif
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label>&nbsp;</label>
                            <br />
                            <div class="form-row add-gallery justify-content-center" id="add-image" style="border:1px solid #ccc;object-fit: cover;
                            height: 39px; border-radius: 5px">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pr-2" >
                            <label>&nbsp;</label>
                            <br>
                            <div class="support-vay2 justify-content-lg-end">
                                <label class="form-control-409 mr-2">
                                    <input type="checkbox" {{old('checked_hightlight')?"checked":""}} name="checked_hightlight">
                                </label>
                                <p>Bài viết nổi bật</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12  p-2">
                    <label>Nội dung <span class="required"></span></label>
                    <textarea id="news_content" class="js-admin-tiny-textarea" name="news_content" style="width: 100%;height: 300px">{{old('news_content')}}</textarea>
                    @if($errors->has('news_content'))
                        <small class="text-danger">
                            {{$errors->first('news_content')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="support-vay2">
                        <label class="form-control-409 mr-2">
                            <input {{old('checked_express')?"checked":""}} type="checkbox" name="checked_express">
                        </label>
                        <p>Đặt làm bài viết quảng cáo</p>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="support-vay2">
                        <label class="form-control-409 mr-2">
                            <input {{old('checked_tutorial')?"checked":""}} type="checkbox" name="checked_tutorial">
                        </label>
                        <p>Đặt làm bài viết hướng dẫn</p>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Từ khóa <span class="required"></span></label>
                    <input value="{{old('meta_key')}}" class="seo form-control" type="text" id="meta_key" name="meta_key" placeholder="Nhập từ khóa ...">
                    @if($errors->has('meta_key'))
                        <small class="text-danger">
                            {{$errors->first('meta_key')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Tiêu đề <span class="required"></span></label>
                    <input value="{{old('meta_title')}}" class="seo form-control meta_title" type="text" name="meta_title" placeholder="Nhập tiêu đề ...">
                    @if($errors->has('meta_title'))
                        <small class="text-danger">
                            {{$errors->first('meta_title')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Đường dẫn <span class="required"></span></label>
                    <input value="{{old('meta_url')}}" class="seo form-control meta_url" type="text" name="meta_url" placeholder="Nhập đường dẫn ...">
                    @if($errors->has('meta_url'))
                        <small class="text-danger">
                            {{$errors->first('meta_url')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-2">
                    <label>Mô tả <span class="required"></span></label>
                    <input value="{{old('meta_desc')}}" class="seo form-control" type="text" id="meta_desc" name="meta_desc" placeholder="Nhập mô tả ...">
                    @if($errors->has('meta_desc'))
                        <small class="text-danger">
                            {{$errors->first('meta_desc')}}
                        </small>
                    @endif
                </div>

                <div class="col-6 mt-2">
                    <button type="submit" class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
                </div>
                <div class="col-6 mt-2">
                    <button type="button" class="btn btn-secondary" style="border-radius: 0;" onclick="window.location.reload()">Làm lại</button>
                </div>

            </div>
        </form>
    </section>
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
    <!-- /.content -->
@endsection
@section('Script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js'></script>

    <script type="text/javascript">
        function resetValue() {
            $('#image_url').val("");
        }
        function resetValueAds() {
            $('#image_url').val("");
        }

        @if(count($errors) > 0)
            toastr.error("Vui lòng kiểm tra các trường");
        @endif
        @if(old('image_url'))
            previewOneImgFromInputText('#image_url', '#add-image', '', '{{old('image_url')}}')
        @endif

        @if(old('image_ads_url'))
            previewOneImgFromInputText('#image_url', '#add-image-ads', '', '{{old('image_ads_url')}}')
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

        $(function (){
            $('#news_title').keyup(function (e) {
                $('.meta_title').val(e.target.value).trigger('keyup')
                $('#meta_key').val(e.target.value)
            })

            $('.meta_title').on('keyup',function (){
                $('.meta_url').val(toSlug($('.meta_title').val()));
            });

            getTinyContentWithoutHTML('news_content', 'blur', '#meta_desc', 200)
        })
    </script>

    <script type="text/javascript">
        function handleFiles(event) {
            var files = event.target.files;
            $("#src").attr("src", URL.createObjectURL(files[0]));
            document.getElementById("audio").load();
        }

        document.getElementById("upload").addEventListener("change", handleFiles, false);
    </script>

    <script type="text/javascript">
        $("#input-tags").selectize({
            delimiter: ",",
            persist: false,
            plugins: ["remove_button"],
            create: function (input) {
                return {
                    value: input,
                    text: input
                };
            }
        });
    </script>
    <script src="js/table.js"></script>
@endsection
