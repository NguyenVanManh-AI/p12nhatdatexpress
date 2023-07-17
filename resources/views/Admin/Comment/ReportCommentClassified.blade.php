@extends('Admin.Layouts.Master')
@section('Title', 'Bình luận báo cáo | Tin đăng')
@section('Style')
<style type="text/css">
  .dropdown-toggle::after{
    margin-top: 8px !important;
    float: right !important;
  }
</style>
@endsection
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css") }}">

<div class="row m-0 p-3">
  <div class="col-12 p-0">
    <div class="box-dash mt-4 pt-4">
      <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
      <form id="form-filter-report"  method="get">
        <div class="row m-0 pt-2 ">
          <div class="col-12 col-sm-12 col-md-5 col-lg-5 box_input px-0">
           <div class="input-reponsive-search pr-3">
               <input class="form-control required" type="" name="classified_name" placeholder="Nhập từ khóa" value="{{ app('request')->input('classified_name') }}">
           </div>
         </div>
         <div class="search-reponsive col-12 col-sm-12 col-md-7 col-lg-7 pl-0">
          <div class="row m-0">
            <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-0">
              <div class="search-reponsive ">
                  <select name="report_content" class="form-control select2" style="width: 100%;height: 34px !important;">
                    @if(app('request')->input('report_content'))
                    <option selected="selected" value="{{ app('request')->input('report_content') }}">{{ app('request')->input('report_content') }}</option>
                    @else
                    <option selected="selected" value="Tất cả">Nội dung báo cáo</option>
                    @endif
                    @foreach ($getReportReasion as $item)
                    <option value="{{$item->content}}">{{$item->content}}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div id="from_date_box" class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
              <div  style="position: relative">
                @if(app('request')->input('from_date') == "")
                <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
                  <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div>

                </div>
                @endif
                  <label for="handleDateFrom"></label><input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
              </div>
            </div>
            <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">
              <div  style="position: relative">
               @if(app('request')->input('to_date') == "")
               <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
                <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>
              </div>
              @endif
                  <label for="handleDateTo"></label><input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
              <div id="appendDateError"></div>
            </div>

          </div>
          <script type="text/javascript">
            $('#from_date_box').click(function(){
              $('#from_date_text').hide();
            })
            $('#to_date_box').click(function(){
              $('#to_date_text').hide();
            })
            setMinMaxDate('#handleDateFrom', '#handleDateTo')
            function setMinMaxDate(inputElementStart, inputElementEnd){
              if ($(inputElementStart).val()) $(inputElementEnd).attr('min',$(inputElementStart).val());
              if ($(inputElementEnd).val()) $(inputElementStart).attr('max',$(inputElementEnd).val());

              $(inputElementStart).change(function (){
                $(inputElementEnd).attr('min',$(inputElementStart).val());
              });
              $(inputElementEnd).change(function (){
                $(inputElementStart).attr('max',$(inputElementStart).val());
              });
            }
          </script>
        </div>
      </div>





      <div class="container pb-3">
        <div class="row">
          <div class="mtdow10 col text-center">
           <button id="filter-report" class=" mtdow10 search-button btn btn-primary w-100 mt-1" style="width: 130px !important"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>
         </div>
       </div>
     </div>
   </div>
 </form>
</div>
</div>
</div>



<!-- Main content -->
<section class="content hiden-scroll mt-3" >
  <div class="container-fluid">
    <div class="row" >
      <div class="col-12">
        <div class="table-responsive" >
          <form action="{{route('admin.comment.trashlist-comment-classified')}}" id="formtrash" method="post">
            @csrf
             <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                 <th scope="row" class="active" width="3%">
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                 <th scope="col" style="width: 4%">STT</th>

                 <th scope="col" style="width: 15%">Người viết</th>
                 <th scope="col" style="width: 26%">Nội dung bình luận</th>
                 <th scope="col" style="width: 26%;min-width: 160px">Nội dung báo cáo</th>
                 <th scope="col" style="width: 9%">Thời gian</th>
                 <th scope="col" style="width: 11%">Tình trạng</th>
                 <th scope="col" style="width: 22%;min-width: 170px">Cài đặt</th>
               </tr>
             </thead>
             <tbody>
              @php
              $countStt = $getListComment->total()+1;
              @endphp
              @foreach ($getListComment as $ListComment)
              @php
              $countStt--;
              @endphp
              <tr>
                <td class="active">
                  <input type="checkbox" class="select-item" value="{{$ListComment->id}}" name="select_item[]">
                  <input type="hidden" class="select-item" value="{{\Crypt::encryptString($ListComment->user_id)}}" name="select_item_created[{{$ListComment->id}}]">
                </td>
                <td>
                  <span>{{$countStt}}</span>
                </td>
                <td class=" pt-4">
                  <span class=" event-title">{{$ListComment->username}}</span><br>
                  <span>
                    @if($ListComment->is_forbidden ==1)
                    (Đã cấm)
                    @endif
                    @if($ListComment->is_locked ==1)
                    (Đã chặn 7 ngày)
                    @endif
                  </span>
                  <br>
                </td>
                <td>
                  {{$ListComment->comment_content}}
                </td>

                <td>
                  <div class="report-reasion toggle-show-more{{$ListComment->id}}" style="font-size: 14px;">
                    @php
                    $count = 0;
                    @endphp
                    @foreach ($getListReport as $ListReport)
                    @if($ListReport->classified_comment_id == $ListComment->id && $ListReport->report_position == 1)
                    <div class="report-reasion-box mb-2">
                      <span>{{$ListReport->report_content}} <span>({{$ListReport->count}})</span></span>
                    </div>
                    @php
                    $count++;
                    @endphp
                    @endif
                    @endforeach
                  </div>
                  @if($count >3)
                  <div id="show-more{{$ListComment->id}}">
                    <p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>
                  </div>
                  @endif
                </td>
                <td class="">
                  <span>{{\Carbon\Carbon::parse($ListComment->report)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i')}}</span>
                </td>
                <td>
                  @include('admin.common.partials._item-status', [
                    'item' => $ListComment,
                    'label' => 'Bình luận'
                  ])
                  @include('Admin.User.partials._user-status', [
                    'user' => $ListComment->user,
                    'label' => 'Tài khoản'
                  ])
                </td>
                <td class="py-2 px-0">
                  <div>
                    <div class="float-left ml-2 mb-1">
                      <i class="icon-setup fa fa-eye mr-2 " ></i>
                      <a  class="text-primary" style="cursor:pointer">Xem</a>
                    </div>
                    <br>
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    @if($ListComment->is_forbidden ==0 && $ListComment->is_locked==0)
                    <div class="float-left ml-2 mb-1">
                      <i class="icon-setup fa fa-history mr-2 " ></i>
                      <a  class="text-primary wrong" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}" style="cursor:pointer">Báo cáo sai</a>
                    </div>
                    <br>
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class="text-danger forbidden" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Cấm tài khoản</a>
                    </div>
                    <br>
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class="text-danger locked" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Chặn tài khoản</a>
                    </div>
                    <br>
                    @endif
                    @endif
                     
                    {{-- cấm tk --}}
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    @if($ListComment->is_forbidden ==1 && $ListComment->is_locked==0)
                    @if($ListComment->is_forbidden == 1)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class=" un-forbidden text-primary" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Mở cấm</a>
                    </div>
                    <br>
                    @endif
                    @endif
                    @if($ListComment->is_locked == 0)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class="text-danger locked" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Chặn tài khoản</a>
                    </div>
                    <br>
                    @endif
                    @endif
                    @endif
                    @endif


                    {{-- chặn --}}

                  @if($check_role == 1  ||key_exists(2, $check_role))
                  @if($ListComment->is_forbidden ==0 && $ListComment->is_locked==1)
                  @if($ListComment->is_locked == 1)
                  @if($check_role == 1  ||key_exists(2, $check_role))
                  <div class="float-left ml-2 cusor-point mb-1">
                    <i class="icon-setup fa fa-times mr-2 "></i>
                    <a  class=" unlocked text-primary" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Mở chặn</a>
                  </div>
                  <br>
                  @endif
                  @endif
                  @if($ListComment->is_forbidden == 0)
                  @if($check_role == 1  ||key_exists(2, $check_role))
                  <div class="float-left ml-2 cusor-point mb-1">
                    <i class="icon-setup fa fa-times mr-2 "></i>
                    <a  class="text-danger forbidden" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Cấm tài khoản</a>
                  </div>
                  <br>
                  @endif
                  @endif
                  @endif
                  @endif

                  {{-- chặn , cấm --}}
                  @if($check_role == 1  ||key_exists(2, $check_role))
                  @if($ListComment->is_forbidden ==1 && $ListComment->is_locked==1)
                  @if($ListComment->is_locked == 1)
                  @if($check_role == 1  ||key_exists(2, $check_role))
                  <div class="float-left ml-2 cusor-point mb-1">
                    <i class="icon-setup fa fa-times mr-2 "></i>
                    <a  class=" unlocked text-primary" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Mở chặn</a>
                  </div>
                  <br>
                  @endif
                  @endif
                  @if($ListComment->is_forbidden == 1)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class=" un-forbidden text-primary" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Mở cấm</a>
                    </div>
                    <br>
                    @endif
                    @endif
                  @endif
                  @endif
                  
                    @if($check_role == 1  ||key_exists(7, $check_role))
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class="text-danger delete" data-id="{{$ListComment->id}}" data-created_by="{{\Crypt::encryptString($ListComment->created_by)}}">Xóa bình luận</a>
                    </div>
                    <br>
                    @else

                    @endif
                    <div class="clear-both"></div>
                  </div>
                </td>  

              </tr>
              <script type="text/javascript">
                $('#show-more{{$ListComment->id}}').click(function(){
                  $('.toggle-show-more{{$ListComment->id}}').toggleClass("show-more");
                  $('#show-more{{$ListComment->id}}').html($('#show-more{{$ListComment->id}}').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
                })
              </script>
              @endforeach
            </tbody>
          </table>
        </form>
      </div>
      <div class="table-bottom d-flex align-items-center justify-content-between  pb-5" style="margin-bottom: 200px !important">
        <div class="text-left d-flex align-items-center">
          <div class="manipulation d-flex mr-4 ">
            <img src="image/manipulation.png" alt="" id="btnTop">
            <div class="btn-group ml-1">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
              aria-expanded="false" data-flip="false" aria-haspopup="true">
              Thao tác
            </button>
            <div class="dropdown-menu">
             @if($check_role == 1  ||key_exists(2, $check_role))
             <a class="dropdown-item wrong-list" type="button" href="javascript:{}">
              <i class="fa fa-undo bg-primary p-1 mr-2 rounded"
              style="color: white !important;font-size: 15px;width: 23px"></i>Báo cáo sai
              <input type="hidden" name="action" value="trash">
            </a>
            <a class="dropdown-item forbidden-list" type="button" href="javascript:{}">
              <i class="fa fa-times bg-danger p-1 mr-2 rounded"
              style="color: white !important;font-size: 16px;width: 23px"></i>Cấm tài khoản
              <input type="hidden" name="action" value="trash">
            </a>
            <a class="dropdown-item un-forbidden-list" type="button" href="javascript:{}">
              <i class="fa fa-undo bg-primary p-1 mr-2 rounded"
              style="color: white !important;font-size: 15px;width: 23px"></i>Mở cấm tài khoản
              <input type="hidden" name="action" value="trash">
            </a>
            <a class="dropdown-item locked-list" type="button" href="javascript:{}">
              <i class="fa fa-times bg-danger p-1 mr-2 rounded"
              style="color: white !important;font-size: 16px;width: 23px"></i>Chặn tài khoản
              <input type="hidden" name="action" value="trash">
            </a>
            <a class="dropdown-item unlocked-list" type="button" href="javascript:{}">
             <i class="fa fa-undo bg-primary p-1 mr-2 rounded"
             style="color: white !important;font-size: 16px;width: 23px"></i>Mở chặn tài khoản
             <input type="hidden" name="action" value="trash">
           </a>
           <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
            style="color: white !important;font-size: 15px"></i>Thùng rác
            <input type="hidden" name="action" value="trash">
          </a>
         
        @else
        <p class="text-center">Không đủ quyền</p>
            @endif
            {{-- @if($check_role == 1  ||key_exists(5, $check_role))
            
            @endif --}}
            
          </div>
        </div>
      </div>

     <div class="d-flex align-items-center justify-content-between mr-2">
      <div class="d-flex mr-0 align-items-center mr-2">Hiển thị</div>
      <label class="select-custom2">
        <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
          <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
          <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
          <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
        </select>
      </label>
    </div>
    <div class="d-flex align-items-center">
      <div class="count-item">Tổng cộng: @empty($ListComment) {{0}} @else {{$getListComment->total()}} @endempty items</div>
      @if($getListComment)
      {{ $getListComment->render('Admin.Layouts.Pagination') }}
      @endif
     </div>

    
</div>

</div>

</div>
</div>
</div>
</section>
<script type="text/javascript">
  $('#show-more').click(function(){
    $('.report-reasion').toggleClass("show-more");
    $('#show-more').html($('#show-more').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
  })
</script>

<script type="text/javascript">
  $('#sukien').addClass('active');
  $('#baocao').addClass('active');
  $('#nav-sukien').addClass('menu-is-opening menu-open');
</script>
<!-- /.content -->
@endsection


@section('Script')
<script src="js/table.js" type="text/javascript"></script>

<script type="text/javascript">
//----------------------------------------------thao tác đơn
$('.wrong').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Xóa báo cáo',
    text: "Báo cáo sai, xóa yêu cầu này",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/wrong-report-comment-classified/" + id + "/" + created_by;
    }
  });
});

$('.forbidden').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Cấm tài khoản',
    text: "Tài khoản sẽ bị cấm lâu dài cho tới khi được admin gỡ cấm!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/forbidden-account/" + id + "/" + created_by;
    }
  });
});
$('.un-forbidden').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Mở cấm tài khoản',
    text: "Tài khoản sẽ được mở cấm!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/forbidden-account/" + id + "/" + created_by;
    }
  });
});
$('.locked').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Chặn tài khoản',
    text: "Tài khoản sẽ bị chặn 1 tuần!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/locked-account/" + id + "/" + created_by;
    }
  });
});

$('.unlocked').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Chặn tài khoản',
    text: "Tài khoản sẽ mở chặn !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/locked-account/" + id + "/" + created_by;
    }
  });
});


$('.delete').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Xác nhận xóa',
    text: "Sau khi xóa sẽ chuyển vào thùng rác!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/delete/" + id + "/" + created_by;
    }
  });
});
$('.blockAccount').click(function () {
  var id = $(this).data('id');
  var created_by = $(this).data('created_by');
  Swal.fire({
    title: 'Khóa tài khoản',
    text: "Sau khi khóa, người dùng sẽ không thể truy cập",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/admin/comment/classified/lock-unlock-account-classified/" + id + "/" + created_by;
    }
  });
});
//----------------------------------------------thao tác list
$('.moveToTrash').click(function () {
  var id = $(this).data('id');
  Swal.fire({
    title: 'Xác nhận xóa',
    text: "Sau khi xóa sẽ chuyển vào thùng rác!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {

      $('#formtrash').submit();

    }
  });
});


$('.forbidden-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/forbidden-account-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Cấm nhiều tài khoản',
    text: "Sau khi cấm, danh sách tài khoản được chọn sẽ đưa về trạng thái cấm",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#formtrash').submit();
    }
  });
});
$('.un-forbidden-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/un-forbidden-account-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Mở nhiều tài khoản',
    text: "Sau khi mở cấm, danh sách tài khoản được chọn sẽ đưa về trạng thái hoạt động",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#formtrash').submit();
    }
  });
});
$('.wrong-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/wrong-report-comment-classified-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Xóa nhiều báo cáo',
    text: "Báo cáo sai, xóa các báo cáo tài khoản được chọn",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#formtrash').submit();
    }
  });
});
$('.locked-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/locked-account-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Chặn nhiều tài khoản',
    text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái chặn",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#formtrash').submit();
    }
  });
});
$('.unlocked-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/un-locked-account-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Chặn nhiều tài khoản',
    text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái chặn",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    cancelButtonText: 'Quay lại',
    confirmButtonText: 'Đồng ý'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#formtrash').submit();
    }
  });
});
</script>
<script type="text/javascript">
  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
</script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/filter-report-comment.validate.js') }}"></script> --}}
@endsection
@section('Style')
<style>
 .dropdown-menu.show {
    display: block;
    margin-top: -36px;
}
</style>
@endsection
