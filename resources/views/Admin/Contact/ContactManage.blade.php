@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách liên hệ | Quản lý liên hệ')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css")}}">
<style type="text/css">
    .contact-button2{
        width: unset;
    }
    .contact-button1, .contact-button2{
        background-color: #00bff3;
    }
    .show_phone{
        cursor: pointer;
        padding-left: 1px;
    }
    .table.text-center, .table.text-center td, .table.text-center th{
        vertical-align: middle;
    }
</style>
@endsection
@section('Content')
<div class="row m-0 p-3">
   <div class="col-12 p-0">
      <div class="contact-box-dash box-dash mt-3 pt-4">
         <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
        <form action="{{route('admin.contact.list')}}" method="GET" id="filterForm">
         <div class="row m-0 pt-2 ">
            <div class="search-reponsive mtdow10 col-12 col-sm-12 col-md-6 col-lg-6 pl-0 pb-3">
               <div class="row m-0">
                  <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
                     <div class="search-reponsive ">
                        <select class="custom-select" name="group_id" id="chuyenmuc">
                           <option selected value="">Chuyên mục</option>
                           <option {{isset($_GET['group_id'])&& $_GET['group_id']==2?"selected":""}} value="2">Nhà đất bán</option>
                           <option {{isset($_GET['group_id'])&& $_GET['group_id']==10?"selected":""}} value="10">Nhà đất cho thuê</option>
                           <option {{isset($_GET['group_id'])&& $_GET['group_id']==18?"selected":""}} value="18">Cần mua - cần thuê</option>
                        </select>
                     </div>
                  </div>
                  <div class="mtdow10 search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
                     <div class="search-reponsive input-reponsive-search pr-3">
                        <select id="mohinh" class="custom-select" name="group_child" >
                           <option value="" selected >Mô hình</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="search-reponsive mtdow10 col-12 col-sm-12 col-md-6 col-lg-6 pl-0 pb-3">
               <div class="row m-0">
                  <div class=" mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
                     <div class="search-reponsive ">
                        <select class="custom-select" name="job" id="nghenghiep">
                           <option selected value="">Nghề nghiệp</option>
                           @foreach ($job as $item )
                           <option {{isset($_GET['job'])&& $_GET['job']==$item->id?"selected":""}} value="{{$item->id}}">{{$item->param_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="mtdow10 search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
                     <div class="search-reponsive input-reponsive-search pr-3">
                        <input class="form-control float-left created_at"
                      type="text"
                        value="{{ isset($_GET['created_at'])&&$_GET['created_at']!=null?$_GET['created_at']:""}}"
                         name="created_at" placeholder="Ngày đăng ký">
                     </div>
                  </div>
               </div>
            </div>
            <div  class="mtdow10 search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pl-0 pb-3">
               <div class="row m-0">
                  <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
                     <div class="search-reponsive">
                        <select class="custom-select" name="province" id="tinhthanhpho">
                           <option selected  value="">Tỉnh / Thành phố</option>
                           @foreach ($province as $item)
                           <option {{isset($_GET['province'])&&$_GET['province']==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->province_name}}</option>
                           @endforeach

                        </select>
                     </div>
                  </div>
                  <div class=" mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
                     <div class="search-reponsive pr-3">
                        <select class="custom-select" name="district" id="quanhuyen">
                           <option  selected value="" >Quận / Huyện</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="search-reponsive mtdow10 col-12 col-sm-12 col-md-6 col-lg-6 pl-0 pb-3">
               <div class="row m-0">
                  <div class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
                     <div class="search-reponsive ">
                        <select class="custom-select" name="list_type" id="loaidanhsach">
                           <option selected value="">Loại danh sách</option>
                        @foreach ($list_type as  $item)
                        <option  {{isset($_GET['list_type'])&&$_GET['list_type']==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->param_name}}</option>
                        @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="mtdow10 search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
                     <div class="search-reponsive input-reponsive-search pr-3">
                        <input class="form-control float-left birthday"
                        type="text"

                         value="{{ isset($_GET['birthday'])&&$_GET['birthday']!=null?$_GET['birthday']:""}}"
                         name="birthday" placeholder="Sinh nhật">
                     </div>
                  </div>
               </div>
            </div>
            <div class="container pb-3 pl-0 pr-3">
               <div class="row">
                  <div class="mtdow10 col text-center">
                     <button type="submit" class="contact-button1 search-button btn mr-2 mb-3 mt-2 text-white font-weight-bold">
                         <i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm
                     </button>
                     <button class="contact-button2  search-button btn mb-2 text-white font-weight-bold" type="button" data-toggle="modal" data-target="#emailMarketing"
                     @if($contact->total() < 1) disabled @endif
                     >
                         <i class="fas fa-chart-pie mr-2" aria-hidden="true"></i>Lên chiến dịch email
                     </button>
                  </div>
               </div>
            </div>
         </div>
        </form>
      </div>
   </div>
</div>
<!-- Main content -->
<section class="content hiden-scroll mt-3">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table style="table-layout: fixed;" class="table table-bordered text-center table-custom" id="table">
                  <thead>
                     <tr class="contact-table">
                        <th scope="col" style="width: 4%">STT</th>
                        <th scope="col" style="width: 15%">Tên khách hàng</th>
                        <th scope="col"  style="width: 8%">Số điện thoại</th>
                        <th scope="col" style="width: 15%">Email</th>
                        <th scope="col" style="width: 15%">Nghề nghiệp</th>
                        <th scope="col" style="width: 6%">Ngày đăng ký</th>
                        <th scope="col" style="width: 12%">Chuyên mục</th>
                        <th scope="col" style="width: 15%">Khu vực</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($contact as $key => $item )
                     <tr>
                        <td>{{ ($contact->currentpage()-1) * $contact->perpage() + $key + 1 }}</td>
                        <td >
                           <p class="mb-0">{{$item->fullname}}</p>
                        </td>
                        <td class="text-left text-center">
                           <span class="text-dark" data-phone="{{$item->phone_number}}">{{\Illuminate\Support\Str::limit($item->phone_number, 6, '')}}<span class="text-primary show_phone" id="show-phone{{$item->id}}">Hiện số</span></span>
                        </td>
                        <td class="">
                           <span>{{$item->email}}</span>
                        </td>
                        <td class="">
                           <span>{{$item->param_name}}</span>
                        </td>
                        <td>
                           <span>{{date('d/m/Y',$item->created_at)}}</span>
                        </td>
                        <td>
                           <span>{{$item->group_name}}</span>
                        </td>
                        <td class="text-success">
                           <span>{{$item->district_name}} ,</span>
                           <span>{{$item->province_name}}</span>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
               <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                  <div class="text-left d-flex align-items-center">
                      <div class="manipulation d-flex mr-4 ">
                          <img src="{{asset('system/image/manipulation.png')}}" alt="" id="btnTop">
                          {{-- <img src="image/manipulation.png" alt="" id="btnTop">
                          <div class="btn-group ml-1">
                              <button type="button" class="btn dropdown-toggle dropdown-custom"
                                      data-toggle="dropdown"
                                      aria-expanded="false" data-flip="false" aria-haspopup="true">
                                  Thao tác
                              </button>
                              <div class="dropdown-menu">
                                  @if($check_role == 1  ||key_exists(5, $check_role))
                                      <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                          <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                             style="color: white !important;font-size: 15px"></i>Thùng rác
                                          <input type="hidden" name="action" value="trash">
                                      </a>
                                  @else
                                      <p>Không đủ quyền</p>
                                  @endif
                              </div>
                          </div> --}}
                      </div>

                      <div class="display d-flex align-items-center mr-4">
                          <span>Hiển thị:</span>
                          <form method="get" id="paginateform" action="{{route('admin.contact.list')}}">
                              <select class="custom-select" id="paginateNumber" name="items" onchange="submitPaginate(event)" >
                                  <option
                                      @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                                      10
                                  </option>
                                  <option
                                      @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">
                                      20
                                  </option>
                                  <option
                                      @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">
                                      30
                                  </option>
                              </select>
                          </form>
                      </div>
                      {{-- @if($check_role == 1||key_exists(8, $check_role))
                          <div class="view-trash">
                              <a href="{{route('admin.classified.listtrash')}}" class=" text-primary"><i class="text-primary far fa-trash-alt"></i>
                                  Xem thùng rác</a>
                              <span class="count-trash">{{$trash_count}}</span>
                          </div>
                      @endif --}}
                  </div>
                  <div class="d-flex align-items-center">
                      <div class="count-item">Tổng cộng: {{$contact->total()}} items</div>
                      @if($contact)
                          {{ $contact->render('Admin.Layouts.Pagination') }}
                      @endif
                  </div>
              </div>

            </div>

         </div>
      </div>
   </div>
</section>

<!-- Modal -->
<div class="modal fade" id="emailMarketing" tabindex="-1" role="dialog" aria-labelledby="emailMarketingTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Lên chiến dịch email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="campaignForm">
            <div class="modal-body">
                <div class="w-100 p-0 br1 pb-2">
                    <div class=" col-12 py-2">
                        <div class="w-100 bar-box">
                            <p class="text-center mb-1 font-weight-bold text-black">TÊN CHIẾN DỊCH & NỘI DUNG MAIL</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <label>Nhập tên chiến dịch <span class="required"></span></label>
                        <input class="form-control required" type="text" name="campaign_name" placeholder="Nhập nội dung">
                        <small class="error text-danger" id="error-campaign_name"></small>
                    </div>
                    <div class="col-12 pt-2">
                        <label>Chọn mẫu mail gửi <span class="required"></span></label>
                        <div class="row m-0">
                            <select  name="mail_template_id" style="width: 100%;height: 38px !important;">
                            @foreach ($templateEmail as $item )
                                <option value="{{$item->id}}">{{$item->template_title}}</option>
                            @endforeach
                            </select>
                        <small class="error text-danger" id="error-mail_template_id"></small>
                        </div>
                    </div>
                </div>

                <div class="w-100 p-0 mt-3 br1">
                    <div class="col-12 p-0">
                        <div class="w-100 bar-box">
                            <p class="text-center mb-1 font-weight-bold text-black">LỊCH TRÌNH GỬI</p>
                        </div>
                    </div>
                    <div class="row m-0 pb-2">
                        <div class="col-6 px-2 pb-2 mt-2 pl-3">
                            <label>Ngày gửi </label>
                            <div class="input-box">
                                <div class="disble-input-send-date">
                                </div>
                                <input id="date-send" class="form-control float-right"
                                       type="date" name="start_date">
                                <div id="error-date"></div>
                            </div>

                        </div>
                        <div class="col-6 px-2 pb-2 mt-2 pr-3">
                            <label>Thời gian </label>
                            <div class="d-flex align-items-center">
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
                                <small class="error text-danger d-block" id="error-start_date"></small>
                                <small class="error text-danger d-block" id="error-start_time_hour"></small>
                                <small class="error text-danger d-block" id="error-start_time_min"></small>
                            </div>
                        </div>
                        <div class="col-12 p-3">
                            <div class="support-vay2 d-flex">
                                <p class="font-weight-bold fz95">Tự động gửi mail chúc mừng sinh nhật</span></p>
                                <label class="form-control-409 ml-2">
                                    <input id="check-birthday" type="checkbox"
                                           name="is_birthday">
                                </label>
                            </div>
                            <p class="text-secondary mb-0 mt-1 fz95">Lưu ý: Chọn ngày gửi hoặc chọn kiểu chúc mừng sinh nhật.
                                <br>
                                Chiến dịch sẽ được lên theo kết quả của bộ lọc.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button id="create-campaign" data-href="{{route('admin.contact.campaign')}}" type="button" class="btn btn-primary">Lên chiến dịch</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('Script')
<script type="text/javascript">
$(document).ready(function(){
   if($('.birthday').val()!=""){
      $('.birthday').attr('type','date');

   }
   if($('.created_at').val()!=""){
      $('.created_at').attr('type','date');

   }
});
$('.birthday').click(function(){
      if($('.birthday').val()==""){
         $('.birthday').attr('type','date');
      }
   });
   $('.birthday').change(function(){
      if($('.birthday').val()==""){
         $('.birthday').attr('type','text');
      }
      else{
         $('.birthday').attr('type','date');

      }
   });
   $('.created_at').click(function(){
      if($('.created_at').val()==""){
         $('.created_at').attr('type','date');
      }
   });
   $('.created_at').change(function(){
      if($('.created_at').val()==""){
         $('.created_at').attr('type','text');
      }
      else{
         $('.created_at').attr('type','date');

      }
   });
</script>
<script type="text/javascript">
   $('.show_phone').click(function(){
     $(this).hide();
       $(this).parent().html($(this).parent().data('phone'))
   });
   function submitPaginate(event){
       const uri = window.location.toString();
       const exist = uri.indexOf('?')
       const existItems = uri.indexOf('?items')
       const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
       exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
   }
   $("#filterForm").on("submit",function(e){
       $(this).append('<input type="hidden" name="items" value="'+ $('#paginateNumber').val() + '" /> ');
   });
</script>
<script>
   $('#chuyenmuc').change(function (){
       if($('#chuyenmuc').val()!=""){
         //  alert($('#chuyenmuc').val());
           var url = "{{route('param.get_child')}}";
           var group_id = $('#chuyenmuc').val();
           $.ajax({
               url: url,
               type: "GET",
               dataType: "json",
               data: {
                   group_id: group_id
               },
               success: function (data) {
                  // alert(data);
                   $('#mohinh').html('');
                   $('#mohinh').append(data['group_child']);
               }
           });
       }
       else{
           $('#mohinh').append('<option selected disabled>Mô hình</option>');
       }
   });
      @if(isset($_GET['group_id'])&& $_GET['group_id']!="")
      var url = "{{route('param.get_child')}}";
       var group_id = "{{$_GET['group_id']}}";
       $.ajax({
           url: url,
           type: "GET",
           dataType: "json",
           data: {
               group_id: group_id
           },
           success: function (data) {
               $('#mohinh').html('');
               $('#mohinh').append(data['group_child']);
               @if(isset($_GET['group_child'])&& $_GET['group_child']!="")
               $('#mohinh').val("{{$_GET['group_child']}}").change();
               @endif
           }
       });
   @endif
</script>
<script>
   $('#tinhthanhpho').change(function (){
         if($('#tinhthanhpho').val()!=""){
              var url = "{{route('param.get_district')}}";
              var group_id = $('#tinhthanhpho').val();
              $.ajax({
                  url: url,
                  type: "GET",
                  dataType: "json",
                  data: {
                     province_id: group_id
                  },
                  success: function (data) {
                      $('#quanhuyen').html('');
                      $('#quanhuyen').append(data['districts']);
                      district_value ="<option value=''>Quận / Huyện</option>";
                      $.each(data['districts'], function (index, value) {
                        let selected = "";
                        district_value+="<option value='" + value.id + "' " + selected + " >" + value.district_name + "</option>";
                     });
                     $('#quanhuyen').append(district_value);
                  }
              });
         }
         else{
              $('#quanhuyen').html('<option selected disabled value>Quận / Huyện</option>');
            }
      });
      @if(isset($_GET['province']) && $_GET['province'] !="")
                  var province_id = "{{$_GET['province']}}";
                  var url = "{{route('param.get_district')}}";
                  $.ajax({
                  url: url,
                  type: "GET",
                  dataType: "json",
                  data: {
                     province_id: province_id
                  },
                  success: function (data) {
                      $('#quanhuyen').html('');
                      $('#quanhuyen').append(data['districts']);
                      district_value ="<option value=''>Quận / Huyện</option>";
                      $.each(data['districts'], function (index, value) {
                        let selected = "";
                        let choose  = "{{$_GET['district']}}";
                        if(choose == value.id) selected ="selected";
                        district_value+="<option "+selected+" value='" + value.id + "'>" + value.district_name + "</option>";
                     });
                     $('#quanhuyen').append(district_value);
                  }
              });
         @endif
  </script>
<script src="js/table.js"></script>
<script>
    $(function () {
        $('#create-campaign').click(function () {
            const url = $(this).data('href')

            // Clear all errors
            $('.error').empty();

            // Get all filter
            let form_filter = get_data_form('#filterForm');
            let form_campaign = get_data_form('#campaignForm');
            let overcome = {...form_filter, ...form_campaign};

            // remove empty
            for (const key in overcome) {
                if (overcome[key] === '') {
                    delete overcome[key];
                }else{
                    overcome[key] = decodeURI(overcome[key])
                }
            }

            // Send ajax create a new campaign
            $.ajax({
                url,
                type: "post",
                data: {...overcome, _token: "{{csrf_token()}}"},
                success: function (result) {
                    if(result.url){
                        window.location.href = result.url
                    }
                },

                error: function(XMLHttpRequest) {
                    const errors = XMLHttpRequest.responseJSON.errors;
                    for (const key in errors) {
                        $('#error-' + key).html(errors[key])
                    }
                }
            })
        })
    })

    function get_data_form(form){
        let data = $(form).serialize().split('&');
        let fields = [];

        for(let i in data){
            data[i] = data[i].split('=');
            fields[ data[i][0] ] = data[i][1];
        }
        return {...fields};
    }
</script>
@endsection
