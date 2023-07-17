@extends('Admin.Layouts.Master')
@section('Title', 'Thêm sự kiện | Sự kiện')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("frontend/css/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("frontend/css/responsive.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("frontend/css/responsiveb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("frontend/css/responsiveh.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("frontend/css/responsivehn.css")}}">
    <style>
        .upload-item .buttons input[name="image_invitation_url"],
        .upload-item .buttons input[name="image_header_url"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 55px);
            opacity: 0;
        }
    </style>
@endsection
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
            <ol class="breadcrumb mt-1 align-items-center">
                <li class="recye px-2">
                    <a href="{{route('admin.event.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
                @if($check_role == 1  ||key_exists(5, $check_role))
                    <li class="phay ml-2">
                        /
                    </li>
                    <li class="recye px-2 ml-1">
                        <a href="{{route('admin.event.trash')}}">
                            <i class="far fa-trash-alt mr-1"></i>Thùng rác
                        </a>
                    </li>
                @endif
            </ol>
        </div>
    <!-- ./Breakcum -->
{{--    <div class="content-header">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row mb-2">--}}
{{--                <div class="col-sm-12 text-left">--}}
{{--                    <h4 class="m-0 font-weight-bold text-uppercase">Sửa sự kiện</h4>--}}
{{--                </div><!-- /.col -->--}}
{{--            </div><!-- /.row -->--}}
{{--        </div><!-- /.container-fluid -->--}}
{{--    </div>--}}
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="create-event" style="display: block; position: relative; width: 100%; transform: unset; top: 0; left: 0">
               <div class="col-12 d-flex justify-content-center w-100">
                   <div class="title" style="width: 98%">
                       Thêm sự kiện
                   </div>
               </div>
                <div class="col-12 wrapper">
                    <form method="post" class="form form-add form-popup" action="{{route('admin.event.add')}}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for=""> Tên sự kiện</label>
                                <input type="text" class="form-control" name="event_title" onblur="changeToSlug(this, '#event_url')" id="event_title" placeholder="Tên sự kiện" value="{{old('event_title')}}">
                                @if($errors->has('event_title'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('event_title')}}
                                    </small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Ngày diễn ra</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', 'Y-m-d') }}">
                                @if($errors->has('start_date'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('start_date')}}
                                    </small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Địa điểm tổ chức</label>
                                <input type="text" class="form-control" placeholder="Địa điểm tổ chức" name="take_place" value="{{old('take_place')}}">
                                @if($errors->has('take_place'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('take_place')}}
                                    </small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Thời gian diễn ra sự kiện</label>
                                <input type="time" class="form-control" placeholder="Thời gian diễn ra sự kiện"  name="start_time" value="{{ old('start_time') ?? date('H:i')}}">
                                @if($errors->has('start_time'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('start_time')}}
                                    </small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label class="w-100" for="">Nổi bật</label>
                                <div class="on-off-toggle">
                                    <input class="on-off-toggle__input" type="checkbox" id="project_highlight" name="is_highlight" value="1" {{ old('is_highlight') ? 'checked' : '' }} />
                                    <label for="project_highlight" class="on-off-toggle__slider"></label>
                                </div>
                            </div>

                                <div class="col-12">
                                    <label for="">Nội dung sự kiện</label>
                                    <textarea id="event_content" name="event_content" class="js-admin-tiny-textarea" placeholder="Nội dung sự kiện">{{old('event_content')}}</textarea>
                                    @if($errors->has('event_content'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('event_content')}}
                                        </small>
                                    @endif
                                </div>

                        @if($errors->has('event_content'))
                                <small class="text-danger error-message-custom">
                                    {{$errors->first('event_content')}}
                                </small>
                            @endif

                        </div>

                        <div class="upload">
                            <div class="upload-item">
                                <h4>Tải lên ảnh bìa sự kiện <span>*</span></h4>
                                <div class="wrap-out" data-target="#modalCoverImage" data-toggle="modal">
                                    <div class="wrap-in">
                                        <img src="{{asset('system/images/icons/upload-file.png')}}">
                                        <div class="logo-note">
                                           Tải ảnh lên
                                        </div>
                                        <p>Bạn chỉ có thể chọn một hình ảnh để làm ảnh bìa sự kiện.</p>
                                        <div class="buttons">
                                            <div class="button button-upload" style="padding: 5px; pointer-events: none">Tải ảnh lên</div>
{{--                                            <div class="button button-select">Chọn ảnh có sẵn</div>--}}
                                            <input type="text" id="image_header_url" name="image_header_url" value="{{old('image_header_url')}}" onchange="changeImageHeader(this)">
                                        </div>
                                    </div>
                                </div>
                                @if($errors->has('image_header_url'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('image_header_url')}}
                                    </small>
                                @endif
                            </div>

                            <div class="upload-item">
                                <h4>Thư mời (nếu có)</h4>
                                <div class="wrap-out">
                                    <div class="wrap-in" data-target="#modalInvationImage" data-toggle="modal">
                                        <img src="{{asset('system/images/icons/upload-file.png')}}">
                                        <div class="logo-note">
                                            Tải ảnh lên
                                        </div>
                                        <p>Bạn chỉ có thể chọn một hình ảnh để làm ảnh thư mời sự kiện.</p>
                                        <div class="buttons">
                                            <div class="button button-upload" style="padding: 5px; pointer-events: none">Tải ảnh lên</div>
{{--                                            <div class="button button-select">Chọn ảnh có sẵn</div>--}}
                                            <input type="text" id="image_invitation_url" name="image_invitation_url" value="{{old('image_invitation_url')}}" onchange="changeImageInvitation(this)">
                                        </div>
                                    </div>
                                </div>
                                @if($errors->has('image_invitation_url'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('image_invitation_url')}}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="upload-images d-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="upload-image">
                                        <img id="image_header_url_preview" class="image_header_url upload-img w-100" src="" style="object-fit: contain">
                                        <i class="fas fa-times-circle remove-img" data-input="image_header_url" ></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="upload-image">
                                        <img class="image_invitation_url upload-img w-100" src="" style="object-fit: contain">
                                        <i class="fas fa-times-circle remove-img" data-input="image_invitation_url"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Vị trí diễn ra sự kiện <span class="text-danger">*</span></label>

                            <div class="row" id="locationRequest">
                                <div class="form-group col-md-4">
                                    <input type="text" class="form-control" placeholder="Địa chỉ" id="address" name="address" value="{{old('address')}}">
                                    @if($errors->has('address'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('address')}}
                                        </small>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="province_id" id="province" class="form-control select2"
                                            onchange="get_district(this, '{{route('param.get_district')}}', '#district')">
                                        <option @if(isset($_GET['province_id']) && $_GET['province_id'] == '') {{ 'selected' }} @endif value="">Tỉnh / Thành phố</option>
                                        @foreach($province as $p)
                                            <option @if(old('province_id')==$p->id) {{ 'selected' }} @endif value="{{$p->id}}">{{$p->province_name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('province_id'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('province_id')}}
                                        </small>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <select class="form-control select2" name="district_id" id="district">
                                        <option value="">Quận / Huyện</option>
                                    </select>
                                    @if($errors->has('district_id'))
                                        <small class="text-danger error-message-custom">
                                            {{$errors->first('district_id')}}
                                        </small>
                                    @endif
                                </div>

                                @if($errors->has('map_latitude') || $errors->has('map_longtitude'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('map_latitude') ?? $errors->first('map_longtitude')}}
                                    </small>
                                @endif
                            </div>

                            <div class="iframe">
                                <div class="map-box map-box-reponsive col-12 w-100" style="height: 600px">
                                    <div id="map" class="w-100 h-100"></div>
                                    <input type="hidden" name="map_latitude" id="map_latitude" value="{{old('map_latitude')}}">
                                    <input type="hidden" name="map_longtitude" id="map_longtitude" value="{{old('map_longtitude')}}">
                                    <small class="text-danger error-message-custom" id="msg_map_longtitude"></small>
                                </div>
                                <div class="note">Chọn vị trí trên bản đồ để thay đổi ghim</div>
                            </div>
                        </div>

{{--                        <div class="top">--}}
{{--                            <div class="on-off">--}}
{{--                                <!-- <img src="../images/on-off.png"> -->--}}
{{--                                <div class="holder holder-off">--}}
{{--                                    <div class="under">--}}
{{--                                        <div class="on">ON</div>--}}
{{--                                        <div class="handler"></div>--}}
{{--                                        <div class="off">OFF</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="note">--}}
{{--                                <p>Sau khi bật sự kiện sẽ đứng đầu danh sách.</p>--}}
{{--                                <p><strong>Phí duy trì: <span class="red">1000 coin/Ngày</span></strong></p>--}}
{{--                                <p>Bạn có: <span class="green"><strong>3000 coin</strong></span></p>--}}
{{--                            </div>--}}

{{--                        </div>--}}
                        <input type="submit" value="Thêm sự kiện" class="mb-4"/>
{{--                        <p class="last">* Sự kiện có thể được  kiểm duyệt lên tới 48 giờ trước khi hiển thị trên website</p>--}}
                        <div class="box-seo-main-reponsive row mt-4 mb-5 d-none">
                            <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pr-2">
                                <label>Đường dẫn thân thiện</label>
                                <input class="form-control required" id="event_url" name="event_url" value="{{old('event_url')}}" />
                                @if($errors->has('event_url'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('event_url')}}
                                    </small>
                                @endif
                            </div>
                            <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pl-2">
                                <label>Tiêu đề dự án</label>
                                <input class="form-control required" id="meta_title" name="meta_title" value="{{old('meta_title')}}" />
                                <small class="text-danger error-message-custom" id="msg_meta_title"></small>
                                @if($errors->has('meta_title'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('meta_title')}}
                                    </small>
                                @endif
                            </div>
                            <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pr-2 tach-input"
                                 style="height: 100px">

                                <label>Từ khóa trên công cụ tìm kiếm</label>
                                <textarea class="w-100 p-2" name="meta_key" id="meta_keyword" rows="5" style="height: auto;">{{old('meta_key')}}</textarea>
                                <small class="text-danger error-message-custom" id="msg_meta_key"></small>
                                @if($errors->has('meta_key'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('meta_key')}}
                                    </small>
                                @endif
                            </div>
                            <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pl-2">
                                <label>Mô tả trên công cụ tìm kiếm</label>
                                <textarea class="w-100 p-2" name="meta_desc" rows="5" id="meta_desc">{{old('meta_desc')}}</textarea>
                                <small class="text-danger error-message-custom" id="msg_meta_desc"></small>
                                @if($errors->has('meta_desc'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('meta_desc')}}
                                    </small>
                                @endif
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCoverImage" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
            <div class="modal-dialog modal-file" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_header_url}}" frameborder="0"
                                style="width: 100%; height: 70vh"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalInvationImage" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
            <div class="modal-dialog modal-file" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_invitation_url}}" frameborder="0"
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
    <script
        src="https://maps.googleapis.com/maps/api/js?key={!! $google_api_key !!}&callback=callback&libraries=&v=weekly"
        async></script>
    <script type="text/javascript" src="js/map.js"></script>

    <script>
         let isClicked = false;
        function callback(){
            let initLocation = {lat: {{old('map_latitude')??14.5555}}, lng:{{old('lmap_longtitude')??15.64453}} }
            initMap('map','input#map_latitude','input#map_longtitude', initLocation);
        }
        async function callbackMarkerToAddress(result){
            $('#address').val('')
            isClicked = true;
            let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}' , '#province')
            await sleep(500)
            const district = await get_district_by_name( result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#district')
            await sleep(500)
            const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#ward')
            await sleep(500)
            if (ward['ward']?.id != null){
                $('#address').val(result.road_name)
            }
            isClicked = false
        }
         // map - Chinh
         $('#locationRequest input[type="text"]').blur(function () {
             if ($(this).val() === '')
                 return;
             setGoogleMap('#road', '#ward', '#district', '#province')
         })
         $('#locationRequest select').change(function () {
             if (!isClicked)
                 setGoogleMap('#road', '#ward', '#district', '#province')
         })

        function changeImageHeader(element) {
            $('.image_header_url').prop('src', element.value)
            $(".image_header_url").parents(".upload-image").css({ opacity: "1", height: "auto" });
        }

        function changeImageInvitation(element) {
            $('.image_invitation_url').prop('src', element.value)
            $(".image_invitation_url").parents(".upload-image").css({ opacity: "1", height: "auto" });
        }

        $(function () {
                $('#event_title').keyup(function () {
                    $('#meta_title').val($(this).val())
                    $('#meta_keyword').html($(this).val())
                })
                // $('#event_content').keyup(function () {
                //     $('#meta_desc').html(fixedSizeString(this.value, 200).replace(/\s{2,}/g, ' ').trim())
                // })
                // Get content tinymce
                getTinyContentWithoutHTML('event_content', 'blur', '#meta_desc', 200)


            @if(old('image_header_url') )
                    $('.image_header_url').prop('src', '{{asset(old('image_header_url'))}}')
                    $(".image_header_url").parents(".upload-image").css({ opacity: "1", height: "auto" });
                @endif

                @if(old('image_invitation_url') )
                    $('.image_invitation_url').prop('src', '{{asset(old('image_invitation_url'))}}')
                    $(".image_invitation_url").parents(".upload-image").css({ opacity: "1", height: "auto" });
                @endif

                $("body").on("click", ".remove-img", function (event) {
                    event.preventDefault();
                    let input = $(this).data("input");
                    $(this).parents(".form").find('input[name="' + input + '"]').val(null);
                    $(this).parents(".upload-image").css({ opacity: "0", height: "0" });
                    $(this).siblings(".upload-img").attr("src", "");
                });

                @if( (old('province_id')) && (old('district_id')) )
                    get_district('#province', '{{route('param.get_district')}}', '#district', {{old('province_id')}}, {{old('district_id')}})
                @endif

                $('#locationRequest select').change(function () {
                    if (!isClicked)
                        setGoogleMap('#address', '#ward', '#district', '#province')
                })

                // Handle change address
                $('#locationRequest input[type="text"]').blur(function () {
                    if ($(this).val() === '')
                        return;
                    setGoogleMap('#address', '#ward', '#district', '#province')
                })


            // Reset form
                $('#reset_btn').click(function () {
                    $('input[type="text"]').val('').attr('value','')
                    $('.select2').prop('selectedIndex', 0).trigger('change');
                    $('textarea').val('')
                    toastr.success('Làm mới thành công');
                })
                // Reset form
                $('.clear-input').click(function () {
                    $(this).parent().find('input').val('')
                })
        });
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
