@extends("Admin.Layouts.Master")
@section('Title', 'Thiết lập | Quản lý dự án')
@section('Style')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style type="text/css">
        #error {
            background: red;
        }
    </style>
@endsection

@section('Content')
    <div class="card-header border-bottom mt-3" style="border-bottom: 0px !important">
        <h5 class="m-0 text-center font-weight-bold">THIẾT LẬP</h5>
    </div>
    <ul class="list-group list-group-flush" style="overflow-x: hidden;">
        <li class="list-group-item p-3">
            <div class="row m-0">
                <div class="col p-0">
                    @if($check_role == 1  ||key_exists(2, $check_role))
                        <form id="edit-setting-project" action="{{route('admin.project.post-project-setting')}}"
                              method="post">
                            @endif
                            @csrf
                            <div class="row m-0">
                                @foreach($getIcon as $item)
                                    <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                                        <div class="form-group">
                                            <label for="image_url">{{$item->description}}</label>
                                            <div>
                                                <div class="d-flex">
                                                    <div style="width: calc(100% - 38px);height: 38px;">
                                                        <div style="position: relative;width: 100%">
                                                            <div class="input-group-prepend clear-input"
                                                                 style="cursor: pointer;height: 38px;position: absolute"
                                                                 onclick="resetValue({{$item->id}})">
                                                                <label class="input-group-text"
                                                                       for="validatedInputGroupSelect">
                                                                    <img src="images/icons/icon_clear.png">
                                                                </label>
                                                            </div>
                                                            <div class="input-group-prepend " data-toggle="modal"
                                                                 data-target="#exampleModal"
                                                                 style="cursor: pointer;height: 38px;position: absolute;left: calc(100% - 100px);"
                                                                 onclick="changeFieldValue({{$item->id}})">
                                                                <label class="input-group-text"
                                                                       style="cursor: pointer; ">
                                                                    <span style="font-weight:300">Chọn hình</span>
                                                                </label>
                                                            </div>

                                                            <input onchange="setImagePriview({{$item->id}})"
                                                                   class="form-control pl-5" id="image_url{{$item->id}}"
                                                                   name="icon{{$item->id}}" value="{{$item->image}}"
                                                                   style="width: 100%;border-radius: .25rem">
                                                        </div>
                                                    </div>
                                                    <div style="width: 38px;height: 38px;" class="ml-2">
                                                        <img id="image_priview{{$item->id}}" class=" @if($item->image == '') d-none @endif" src="{{ $item->image }}" width="100%"
                                                             height="100%">
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has('image_url'))
                                                <small class="text-danger error-message-custom">
                                                    {{$errors->first('image_url')}}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document" style="max-width:80% !important;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Chọn file</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe id="filemanager" src=""
                                                        style="width: 100%;height: 60vh"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                                    <label>Số tin ngắt trang</label>
                                    <input class="form-control required mb-1" name="ngattrang" type="number"
                                           value="{{$getValue->config_value}}">
                                </div>
                            </div>
                            <div class="text-center">
                                @if($check_role == 1  ||key_exists(2, $check_role))
                                    <button type="submit" class="btn btn-success" onclick="appenHtml()"
                                            style="border-radius: 0;">Lưu
                                    </button>
                                @endif
                            </div>
                            @if($check_role == 1  ||key_exists(2, $check_role))
                        </form>
                    @endif
                </div>
            </div>


    </ul>
@endsection

@section('Script')
    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>

    <script>
        // Default Configuration
        var check = 1;

        function notitication() {
            if (check == 1) {
                toastr.error('Vui lòng kiểm tra các trường');
                check++;
            }
        }
    </script>

    <script>
        function changeFieldValue(id) {
            document.getElementById('filemanager').src = '../responsive_filemanager/filemanager/dialog.php?multiple=0&type=1&field_id=image_url' + id;
        }

        function resetValue(id) {
            $('#image_url' + id).val('');
            $('#image_priview' + id).hide();
        }

        function setImagePriview(id) {
            $('#image_priview' + id).removeClass('d-none')
            $('#image_priview' + id).show();
            $("#image_priview" + id).attr("src", $('#image_url' + id).val());
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
            interity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>

@endsection
