@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách banner | Trang cá nhân')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<form method="get" action="{{route('admin.personal-page.list-banner')}}" id="filter-promotion">
  <div class="box-fiter-reponsive">
    <div class="row m-0 px-3 pb-4 pt-3">
      <div class="search-reponsive col-12 col-sm-12 col-md-9 col-lg-9">
        <div class="row m-0">
            <div class="search-reponsive col-12 col-sm-12 col-md-4 col-lg-4">
                <input name="keyword" class="start_day form-control float-left" type="text" placeholder="Từ khóa( tên, email, số điện thoại)"  value="{{ app('request')->input('keyword') }}" >
            </div>
            <div onclick="hideTextDateStart()" class="col-sm-12 col-lg-4 form-group">
                <div style="position: relative">
                    <div id="txtDateStart" style="width:200px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>
                    <input name="date_from" class="form-control float-right date_start" type="date" value="{{ request('date_from') ?? ""}}" />
                </div>
            </div>
            <div onclick="hideTextDateEnd()" class="col-sm-12 col-lg-4 form-group">
                <div style="position: relative">
                    <div id="txtDateEnd" style="width: 200px;height: 22px;background: #fff;position: absolute;top: 9px;left: 9px;z-index: 1">Đến ngày</div>
                    <input name="date_to" class="form-control float-right date_end" type="date" value="{{request('date_to') ?? ""}}" />
                </div>
            </div>
      </div>
    </div>
    <div class="comment-new-reponsive col-12 col-sm-12 col-md-3 col-lg-3">
      <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search mr-2" aria-hidden="true" ></i>Tìm kiếm</button>
    </div>
  </div>
</div>
</form>
<h4 class="text-center mb-3 font-weight-bold">QUẢN LÝ BANNER</h4>
<!-- Main content -->
<section class="content ">
  <div class="container-fluid" >
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table" style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                  <th scope="row" class="active" width="3%">
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" style="width: 6%">STT</th>
                  <th scope="col" style="width: 20%">Tên</th>
                  <th scope="col" style="width: 6%">Banner trái</th>
                  <th scope="col" style="width: 6%">Banner phải</th>
                  <th scope="col" style="width: 12%">Ngày tạo</th>
                  <th scope="col" style="width: 12%">Trạng thái</th>
                  <th scope="col" style="width: 12%">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @php
                $countStt = $list->total()+1;
                @endphp
                @forelse ($list as $item )
                @php
                $countStt--;
                @endphp
                <tr>
                    <td class="active">
                        <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                        <input type="hidden" class="select-item"
                               name="select_item_created[{{$item->id}}]">
                    </td>
                    <td class="font-weight-bold">{{$countStt}}</td>
                    <td class="font-weight-bold">
                        <p>{{$item->fullname}}</p>
                        <p>{{$item->email}}</p>
                        <p>{{$item->phone_number}}</p>
                    </td>
                    <td class="font-weight-bold">
                        <img src="{{$item->banner_left}}" width="80px">
                    </td>
                    <td class="font-weight-bold">
                        <img src="{{$item->banner_right}}" width="80px">
                    </td>
                    <td>
                        <span>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</span>
                    </td>
                    {{-- <td class="font-weight-bold">
                        @if($item->banner_status == 0)
                            <span class="text-warning">Không hiển thị</span>
                        @else
                            <span class="text-success">Hiển thị</span>
                        @endif
                    </td> --}}
                    <td>
                        <div class="text-left">
                            @if($check_role == 1  ||key_exists(2, $check_role))
                                {{-- @if($item->banner_status == 0) --}}
{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    <div class="ml-2 mb-2 cusor-point">--}}
{{--                                        <i class="fa fa-check mr-2 "></i>--}}
{{--                                        <a class="text-primary approve" data-id="{{$item->id}}"--}}
{{--                                           data-created_by="{{\Crypt::encryptString($item->id)}}">Duyệt banner</a>--}}
{{--                                    </div>--}}
{{--                                    <br>--}}
{{--                                @endif--}}
{{--                                @if($check_role == 1  ||key_exists(2, $check_role))--}}
{{--                                    <div class="ml-2 mb-2 cusor-point">--}}
{{--                                        <i class="fas fa-times mr12"></i>--}}
{{--                                        <a class="setting-item close1 text-danger action_delete cusor-point"--}}
{{--                                           data-id="{{$item->id}}">Từ chối</a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

                                    {{-- <div class="ml-2 mb-2 cusor-point">
                                        <i class="fa fa-check mr-2 "></i>
                                        <a class="text-primary show-banner" href="{{route('admin.personal-page.show-banner', $item->id)}}">Hiển thị</a>
                                    </div>
                                @else
                                    <div class="float-left ml10 mb-2 cusor-point">
                                        <i class="fas fa-times mr12"></i>
                                        <a class="setting-item un-approve text-danger action_delete cusor-point" href="{{route('admin.personal-page.un-approve-banner', $item->id)}}">Ẩn banner</a>
                                    </div>
                                    <div class="float-left ml10 mb-2 cusor-point">
                                        <i class="fas fa-times mr12"></i>
                                        <a class="setting-item remove-banner text-danger action_delete cusor-point" href="{{route('admin.personal-page.close-banner', $item->id)}}">Xóa banner</a>
                                    </div>
                                @endif --}}
                            @endif
                            <div class="clear-both"></div>
                        </div>
                    </td>
                </tr>
              @empty
                    <tr>
                        <td colspan="8">Không có dữ liệu</td>
                    </tr>
              @endforelse
            </tbody>
          </table>
        </form>
      </div>

      <div class=" m-0 mt-3 mb-5 pb-5"  style="width: 100%;">
        <div class="w140 float-left mb-2" >
          <div class="manipulation d-flex mr-4">
            <img src="{{ asset("system/images/icons/manipulation.png")}}" id="btnTop">
            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
            aria-expanded="false" data-flip="false" aria-haspopup="true">
            Thao tác
          </button>
          <div class="dropdown-menu">
            @if($check_role == 1  ||key_exists(2, $check_role))
            <a class="dropdown-item show-list" type="button" href="javascript:{}">
             <i class="fa fa-check bg-success p-1 mr-2 rounded"
             style="color: white !important;font-size: 15px;width: 23px"></i>Hiển thị
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
           @if($check_role == 1  ||key_exists(2, $check_role))
           <a class="dropdown-item un-approve-list" type="button" href="javascript:{}">
             <i class="fa fa-times bg-danger p-1 mr-2 rounded"
             style="color: white !important;font-size: 18px;width: 23px"></i>Ẩn
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
           @if($check_role == 1  ||key_exists(2, $check_role))
           <a class="dropdown-item close-list" type="button" href="javascript:{}">
             <i class="fa fa-undo bg-warning p-1 mr-2 rounded"
             style="color: white !important;font-size: 15px;width: 23px"></i>Gỡ
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
         </div>
       </div>
     </div>
     <div class="float-left mb-2 w150" style="width: 190px">
      <div class="d-flex align-items-center justify-content-between mx-4">
        <div class="d-flex mr-0 align-items-center">Hiển thị</div>
        <label class="select-custom2">
          <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
          </select>
        </label>
      </div>

    </div>
    <div class="float-left mb-2 w160">
      <div class="manipulation d-flex mr-4 pt-1">
      </div>
    </div>
    <div class="float-left mb-2" style="width: calc(100% - 800px);"></div>
    <div class="float-right mb-2 ">
     <div class="d-flex align-items-center">
      <div class="count-item" >Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
      <div class="count-item-reponsive" style="display: none">@empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
      <div class="float-right">
       @if($list)
       {{ $list->render('Admin.Layouts.Pagination') }}
       @endif
     </div>
   </div>
 </div>
 <div class="clear-both"></div>
</div>

</div>
</div>
</div>
</section>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js"></script>

<script>
  @if(count($errors) > 0)
  toastr.error("Vui lòng kiểm tra các trường");
  @endif
</script>

<script>
  $('.close1').click(function () {
    var id = $(this).data('id');
    var created_by = $(this).data('created_by');
    Swal.fire({
      title: 'Không duyệt banner',
      text: "Banner không được duyệt",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/personal-page/close-banner/" + id + "/" + created_by;
      }
    });
  });
  $('.approve').click(function () {
      var id = $(this).data('id');
      var created_by = $(this).data('created_by');
      Swal.fire({
          title: 'Duyệt Banner',
          text: "Sau khi duyệt, banner sẽ được hiển thị",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Quay lại',
          confirmButtonText: 'Đồng ý'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = "/admin/personal-page/approve-banner/" + id + "/" + created_by;
          }
      });
  });
  $('.un-approve').click(function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Ẩn banner',
      text: "Xác nhận ẩn banner",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = $(this).attr('href')
      }
    });
  });
  $('.close-list').click(function () {
    $('#formtrash').attr('action', "/admin/personal-page/close-banner-list")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Không duyệt banner',
      text: "Banner không được duyệt",
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
  $('.approve-list').click(function () {
    $('#formtrash').attr('action', "/admin/personal-page/approve-banner-list")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Duyệt Banner',
      text: "Sau khi duyệt, banner sẽ được hiển thị",
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
  $('.un-approve-list').click(function () {
    $('#formtrash').attr('action', "{{route('admin.personal-page.un-approve-banner-list')}}")
    Swal.fire({
      title: 'Ẩn Banner',
      text: "Sau khi ẩn Banner. Banner sẽ không được hiển thị",
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
  $('.show-list').click(function () {
      $('#formtrash').attr('action', "{{route('admin.personal-page.show-banner-list')}}")
      var id = $(this).data('id');
      Swal.fire({
          title: 'Hiển thị Banner',
          text: "Sau khi xác nhận, banner sẽ được hiển thị",
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
  $('.show-banner').click(function (e) {
      e.preventDefault()
      Swal.fire({
          title: 'Hiển thị banner',
          text: "Xác nhận hiển thị",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Quay lại',
          confirmButtonText: 'Đồng ý'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = $(this).attr('href')
          }
      });
  });
  $('.remove-banner').click(function (e) {
      e.preventDefault()
      Swal.fire({
          title: 'Xóa banner banner',
          text: "Xác nhận xóa banner sẽ không thể khôi phục",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Quay lại',
          confirmButtonText: 'Đồng ý'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = $(this).attr('href')
          }
      });
  });

  $("#filter-promotion").on("submit",function(e){
      $(this).append('<input type="hidden" name="items" value="'+ $('#paginateNumber').val() + '" /> ');
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
  setMinMaxDate('.date_start', '.date_end')

  function hideTextDateStart(){
      $('#txtDateStart').hide();
  }
  function hideTextDateEnd(){
      $('#txtDateEnd').hide();
  }

  @if(request('date_from'))
      hideTextDateStart();
  @endif
  @if(request('date_to'))
      hideTextDateEnd();
  @endif

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/filter-promotion.validate.js') }}"></script>
@endsection
