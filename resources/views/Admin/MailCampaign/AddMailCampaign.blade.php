@extends('Admin.Layouts.Master')
@section('Title', 'Tạo chiến dịch | Chiến dịch email')
@section('Style')
{{-- link css project chính --}}
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
{{-- màu hiển thị thanh toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/select2/3.4.8/select2.css'>
<style type="text/css">
  #error{background: red;}
</style>
<style type="text/css">
    .select2-container {
        height: auto;
    }
    .select2-container .select2-choice{
        height: 36px !important;
        border-radius: 0;
        background-image: none;
        padding: 5px 0 0 8px;
        box-shadow: none !important;
        background: #fff !important;
        border: 0px;
    }
    .select2-container .select2-choice .select2-arrow{
        border-radius: 0;
        background: #fff none;
        border: 0;
    }
    .select2-container .select2-choice .select2-arrow b {
        margin-top: 4px;
    }
    .select2-no-results {
        display: none !important;
    }
    .select2-container-multi.select2-container-active .select2-choices{
        box-shadow: none;
    }
    .disble-input,.disble-input-send-date{
        position: absolute;background: #ccc;width: 100%;height: 38px;z-index: 999;opacity: 0.3;display: none
    }
    .input-box{
        position: relative
    }
</style>
@endsection
@section('Content')
    <section>
        <div>
            <div class="col-sm-12 mbup30">
                <div class="row m-0 px-2 pt-3">
                    <ol class="breadcrumb mt-1">
                        <li class="recye px-2 pt-1 check">
                            <a href="{{route('admin.email-campaign.list-campaign')}}">
                                <i class="fa fa-th-list mr-1"></i>Danh sách
                            </a>
                        </li>
                        {{-- kiểm tra phân quyền thùng rác --}}
                        @if($check_role == 1  ||key_exists(5, $check_role))
                            <li class="phay ml-2 " style="margin-top: 2px !important">
                                /
                            </li>
                            <li class="recye px-2 pt-1 ml-1">
                                <a href="{{route('admin.email-campaign.trash-list-campaign')}}">
                                    Thùng rác
                                </a>
                            </li>
                        @endif
                        {{-- kiểm tra phân quyền thêm --}}
                        @if($check_role == 1  ||key_exists(1, $check_role))
                            <li class="ml-2 phay">
                                /
                            </li>
                            <li class="add px-2 pt-1 ml-1 check active">
                                <i class="fa fa-edit mr-1"></i>Thêm
                            </li>
                        @endif
                    </ol>
                </div>
            </div><!-- /.col -->
            <h4 class="text-center font-weight-bold mb-2 mt-2">TẠO CHIẾN DỊCH</h4>
            <div class="px-2">
                @if($check_role == 1  || key_exists(1, $check_role))
                    <form id="form-add-campaign" method="post"
                          action="{{route('admin.email-campaign.send-filter-user')}}">
                        @csrf
                        <div class="row m-0 px-2 pb-3">
                            <div class="col-12 p-0">
                                <div class="row m-0">
                                    <div id="campent-box" class="  col-12 col-sm-12 col-md-12 col-lg-12 p-0">
                                        <div class="w-100 p-0 br1 pb-2">
                                            <div class=" col-12 p-0">
                                                <div class="w-100 bar-box">
                                                    <p class="text-center mb-0 font-weight-bold text-white">TÊN CHIẾN
                                                        DỊCH & NỘI DUNG MAIL</p>
                                                </div>
                                            </div>
                                            <div class="col-12  px-3 py-2">
                                                <label>Nhập tên chiến dịch <span class="required"></span></label>
                                                <input class="form-control required" type="text" name="campaign_name"
                                                       placeholder="Nhập nội dung">
                                                @if($errors->has('campaign_name'))
                                                    <small class="text-danger error-message-custom">
                                                        {{$errors->first('campaign_name')}}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-12  px-3 py-2">
                                                <label>Chọn mẫu mail gửi <span class="required"></span></label>
                                                <div class="row m-0">
                                                    <div class="col-12 col-sm-12 col-md-9 col-lg-9 p-0">
                                                        <select class="select2" name="mail_template_id"
                                                                style="width: 100%;height: 38px !important;">
                                                            @foreach ($templateEmail as $item )
                                                                <option
                                                                    value="{{$item->id}}">{{$item->template_title}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->has('mail_template_id'))
                                                            <small class="text-danger error-message-custom">
                                                                {{$errors->first('mail_template_id')}}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 pl-2 pdx0i mt20u">
                                                        <a href="{{route('admin.email-campaign.add-mail-template')}}">
                                                            <div class="btn btn-primary w-100"
                                                                 style="border-radius: 0px"><i class="fa fa-plus-circle"aria-hidden="true"></i>
                                                                Tạo mẫu mail
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="w-100 p-0 mt-3 br1">
                                            <div class="col-12 p-0">
                                                <div class="w-100 bar-box">
                                                    <p class="text-center mb-0 font-weight-bold text-white">ĐỐI TƯỢNG NHẬN MAIL</p>
                                                </div>
                                            </div>
                                            <div class="col-12  px-3 pt-2">
                                                <label>Nhập ID hoặc email thành viên</label>
                                                <div class="form-group select-tags" style="margin-bottom: 5px">
                                                    <select multiple id="e1" class="w-100"
                                                            placeholder="Nhập ID hoặc Email" name="list_user[]">
                                                        @foreach ($getuser as $item )
                                                            <option value="{{$item->id}}">[ID: {{$item->id}}
                                                                ] {{$item->email}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <p class="text-secondary mb-0 fz95">Lưu ý khi sử dụng tính năng nhập
                                                    ID hoặc email thành viên thì các chức năng lọc dưới sẽ bị vô hiệu
                                                    hóa. Mail nhập vào là mail đã có trong mục quản lý thành viên
                                                </p>
                                            </div>

                                            <div class="col-12 py-2 ">
                                                <div class="row m-0">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-2 pb-2">
                                                        <label>Chuyên mục</label>
                                                        <div class="input-box">
                                                            <div class="disble-input">
                                                            </div>
                                                            <select class="select2" name="classified_category"
                                                                    style="width: 100%;height: 38px !important;">
                                                                <option value="">Tất cả</option>
                                                                <option
                                                                    {{isset($_GET['group_id'])&& $_GET['group_id']==2?"selected":""}} value="2">
                                                                    Nhà đất bán
                                                                </option>
                                                                <option
                                                                    {{isset($_GET['group_id'])&& $_GET['group_id']==10?"selected":""}} value="10">
                                                                    Nhà đất cho thuê
                                                                </option>
                                                                <option
                                                                    {{isset($_GET['group_id'])&& $_GET['group_id']==18?"selected":""}} value="18">
                                                                    Cần mua - cần thuê
                                                                </option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-2 pb-2">
                                                        <label>Vị trí</label>
                                                        <div class="input-box">
                                                            <div class="disble-input">
                                                            </div>
                                                            <select class="select2" name="province"
                                                                    style="width: 100%;height: 38px !important;">
                                                                <option value="">Tất cả</option>
                                                                @foreach ($province as $item)
                                                                    <option
                                                                        {{isset($_GET['province'])&&$_GET['province']==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->province_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-2 pb-2 pb-2">
                                                        <label>Loại tài khoản</label>
                                                        <div class="input-box">
                                                            <div class="disble-input">
                                                            </div>
                                                            <select class="select2" name="type_account"
                                                                    style="width: 100%;height: 38px !important;">
                                                                <option value="">Tất cả</option>
                                                                @foreach($user_type as $type)
                                                                    <option
                                                                        {{isset($_GET['user_type'])&& $_GET['user_type']== $type->id ?"selected":""}} value="{{$type->id}}">
                                                                        {{$type->type_name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-2 pb-2 pb-2">
                                                        <label>Từ ngày</label>
                                                        <div class="input-box">
                                                            <div class="disble-input">
                                                            </div>
                                                            <input class="form-control float-left" type="date" value=""
                                                                   name="from_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-2 pb-2 pb-2">
                                                        <label>Đến ngày</label>
                                                        <div class="input-box">
                                                            <div class="disble-input">
                                                            </div>
                                                            <input class="form-control float-left" type="date" value=""
                                                                   name="to_date">
                                                        </div>
                                                    </div>
                                                    {{--  <span class="ml-2">Bạn sẽ gửi <span class="text-danger" id="total-mail-send">0</span> mail</span> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-100 p-0 mt-3 br1">
                                            <div class="col-12 p-0">
                                                <div class="w-100 bar-box">
                                                    <p class="text-center mb-0 font-weight-bold text-white">LỊCH TRÌNH
                                                        GỬI</p>
                                                </div>
                                            </div>
                                            <div class="row m-0 pb-2">
                                                <div class="col-6 px-2 pb-2 mt-2 pl-3">
                                                    <label>Ngày gửi</label>
                                                    <div class="input-box">
                                                        <div class="disble-input-send-date">
                                                        </div>
                                                        <input id="date-send" class="form-control float-right"
                                                               type="date" name="start_date">
                                                        <div id="error-date"></div>
                                                    </div>

                                                </div>
                                                <div class="col-6 px-2 pb-2 mt-2 pr-3">
                                                    <label>Thời gian</label>
                                                    <div class="d-flex">
                                                        <div class="input-box" style="width: 48%">
                                                            <div class="disble-input-send-date">
                                                            </div>
                                                            <input id="start-time-hour-input"
                                                                   class="form-control required" placeholder="Giờ"
                                                                   type="number" name="start_time_hour">
                                                            <div id="error-hour"></div>
                                                        </div>

                                                        <div class=" space-area text-center font-weight-bold mx-1">
                                                            :
                                                        </div>
                                                        <div class="input-box" style="width: 48%">
                                                            <div class="disble-input-send-date">
                                                            </div>
                                                            <input id="start-time-min-input"
                                                                   class="form-control required" placeholder="phút"
                                                                   type="number" name="start_time_min">
                                                            <div id="error-min"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row m-0 pb-2">
                                                    <div class="col-12 px-2 pb-2 mt-2 pl-3">
                                                        @if($errors->has('start_date'))
                                                            <small class="text-danger error-message-custom">
                                                                {{$errors->first('start_date')}}
                                                            </small>
                                                        @endif
                                                        @if($errors->has('start_time_hour'))
                                                            <small class="text-danger error-message-custom">
                                                                {{$errors->first('start_time_hour')}}
                                                            </small>
                                                        @endif
                                                        @if($errors->has('start_time_min'))
                                                            <small class="text-danger error-message-custom">
                                                                {{$errors->first('start_time_min')}}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 p-3">
                                                    <div class="support-vay2">
                                                        <p class="font-weight-bold fz95">Tự động gửi mail chúc mừng sinh
                                                            nhật</p>
                                                        <label class="form-control-409 ml-2">
                                                            <input id="check-birthday" type="checkbox"
                                                                   name="is_birthday">
                                                        </label>

                                                    </div>
                                                    <p class="text-secondary mb-0 mt-1 fz95">Lưu ý: Không chọn chức năng
                                                        trọng mục này nếu bạn muốn tạo chiến dịch gửi ngay.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row m-0 pb-3">
                                            <div class="col-6 mt-2">

                                            </div>
                                            <div class="col-12 mt-2 p-0 text-center">
                                                <button type="submit" class="btn btn-primary" style="border-radius: 0;">
                                                    Tạo chiến dịch
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </section>
<!-- /.content -->
@endsection
@section('Script')
    <script src='https://cdn.jsdelivr.net/select2/3.4.8/select2.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
            integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#e1').change(function () {
                if ($('#e1').val() != "") {
                    $('#total-mail-send').text($('#e1').val().length)
                    $('.disble-input').show();
                    $('#form-add-campaign').attr('action', "{{route('admin.email-campaign.send-list-user')}}")
                } else {
                    $('#form-add-campaign').attr('action', "{{route('admin.email-campaign.send-filter-user')}}}")
                    $('.disble-input').hide();
                }
            })
            $('#date-send').change(function () {
                if ($('#date-send').val() != "") {
                    $("#check-birthday").attr("disabled", true);
                } else {
                    $("#check-birthday").attr("disabled", false);
                }
            })
            $('#check-birthday').change(function () {
                if ($('#check-birthday').is(":checked")) {
                    $('.disble-input-send-date').show();
                } else {
                    $('.disble-input-send-date').hide();
                }
            })
        })
    </script>
    <script type="text/javascript">
        $(function () {
            $("#e1").select2({
                placeholder: "Select2 input",
                allowClear: true,
                matcher: function (term, text) {
                    return text.toUpperCase().indexOf(term.toUpperCase()) == 0;
                }
            });
            $('.select2').select2()
        })
    </script>
    <script type="text/javascript">
        var check = 1;

        function notitication() {
            if (check == 1) {
                toastr.error('Vui lòng kiểm tra các trường');
                check++;
            }
        }
    </script>
@endsection
