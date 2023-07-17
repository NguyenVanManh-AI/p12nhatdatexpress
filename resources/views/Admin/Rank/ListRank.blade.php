@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách cấp bậc | Quản lý cấp bậc')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<style type="text/css">
  @media only screen and (max-width: 779px) {
    .box-panage{
      display:inline !important;
    }
  }
</style>
<!-- Content Header (Page header) -->
<div class="col-sm-12 mbup30">
  <div class="row m-0 px-2 pt-3">
    <ol class="breadcrumb mt-1">
      <li class="list-box px-2 pt-1 active check">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </li>
      {{-- @if($check_role == 1  ||key_exists(5, $check_role))
      <li class="phay ml-2 mt-1">
        /
      </li>
      <li class="recye px-2 pt-1 ml-1">
        <a href="{{route('admin.rank.trash')}}">
          Thùng rác
        </a>
      </li>
      @endif --}}
      @if($check_role == 1  ||key_exists(1, $check_role))
      <li class="ml-2 phay mt-1">
        /
      </li>   
      <li class="add px-2 pt-1 ml-1 check">
        <a href="{{route('admin.rank.add')}}">
          <i class="fa fa-edit mr-1"></i>Thêm
        </a>
      </li>
      @endif
    </ol>
  </div>
</div><!-- /.col -->

<!-- Filter -->
<div class="container-fluid px-3 mt-2">
 <form method="get" action="{{route("admin.rank.list")}}">
  <div class="row m-0">
    <div class="col-12 col-sm-12 col-md-3 col-lg-3 box_input px-0 mtdow10 ">
     <div class="input-reponsive-search ">
       <input class="form-control required" type="" name="keyword" placeholder="Nhập từ khóa" value="{{ app('request')->input('keyword') }}">
     </div>
   </div>
   <div class="search-reponsive mtdow10  col-12 col-sm-12 col-md-9 col-lg-9 pl-0">
    <div class="row m-0">
      <div id="from_date_box" class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
        <div  style="position: relative">
          @if(app('request')->input('from_date') == "")
          <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
            <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div> 
          </div>
          @endif
          <input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
        </div>
      </div>
      <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
        <div  style="position: relative">
         @if(app('request')->input('to_date') == "")
         <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
          <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>         
        </div>
        @endif
        <input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
        <div id="appendDateError"></div>
      </div>

    </div>
    <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">         
      <button class=" search-button btn btn-primary w-100" style="height: 37px;"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>                      
    </div>

    <script type="text/javascript">
      $('#from_date_box').click(function(){
        $('#from_date_text').hide();
      })
      $('#to_date_box').click(function(){
        $('#to_date_text').hide();
      })
      // $(document).ready(function(){
      //   $('.start_day').change(function (){
      //     $('.end_day').attr('min',$('.start_day').val());
      //   });
      //   $('.end_day').change(function (){
      //     $('.start_day').attr('max',$('.end_day').val());
      //   });
      // });
    </script>
  </div>
</div>


</div>
</form>
</div>
<!-- ./Filter -->

<div class="content-header" style="margin-top: -40px">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 font-weight-bold">QUẢN LÝ CẤP BẬC</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" style="overflow-x: hidden">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          {{-- <form action="{{route('admin.rank.trashlist')}}" id="formtrash" method="post">
            @csrf --}}
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  {{-- <th scope="row" class="active">
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th> --}}
                  <th scope="col">ID</th>
                  {{--  <th scope="col" width="10%">Thứ tự</th> --}}
                  <th scope="col" width="12%">Hình ảnh</th>
                  <th scope="col" width="10%">Tiêu đề</th>
                  <th scope="col" width="10%">Phần trăm</th>
                   <th scope="col" width="23%">Số tin / Tiền nạp</th>
                  <th scope="col" width="24%">Thông tin</th>
                  <th scope="col" width="14%">Thao tác</th>
                </tr>
              </thead>
              <tbody>

                {{-- @csrf --}}
                @forelse($list as $item)
                <tr>
                  {{-- <td class="active">
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td> --}}
                  <td class="id_role">{{$item->id}}</td>
                  {{-- <td class="input_rounded">
                                            <input type="text" value="{{$item->show_order}}" name="show_order[{{$item->id}}]">
                                          </td> --}}
                  <td>
                    @if($item->image_url)
                    <a data-fancybox="image_{{$item->id}}" data-src="{{asset($item->image_url)}}">
                      <img src="{{asset($item->image_url)}}" width="80px" height="80px" style="object-fit: cover" />
                    </a>
                    @endif
                  </td>
                  <td class="title_role text-wrap" style="word-break: break-word">{{$item->level_name}}</td>
                  <td class="title_role text-wrap" style="word-break: break-word">{{$item->percent_special}}%</td>
                  <td class="title_role text-wrap text-left" style="word-break: break-word">
                    <p class="mt-2"><span class="text-gray mr-2">Số tin đăng:</span>từ {{$item->classified_min_quantity}} tin đến {{$item->classified_max_quantity}} tin</p>
                    <p class="mt-2"><span class="text-gray mr-2">Số tiền nạp:</span>từ {{number_format($item->deposit_min_amount,0,'','.')}}đ đến {{number_format($item->deposit_max_amount,0,'','.')}}đ</p>         
                  </td>
                  <td >
                    <p class="mt-2"><span class="text-gray mr-2">Ngày tạo:</span>{{date('d/m/Y H:i:s',$item->created_at)}}</p>
                    <p class="mb-2"><span class="text-gray mr-2 ">Cập nhật:</span>{{date('d/m/Y H:i:s',$item->updated_at ?? $item->created_at)}}</p>
                  </td>
                  <td class="text-left">
                    <div>
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      <div class="float-left ml-2">
                        <i class="fas fa-cog mr-2"></i>
                        <a href="{{route('admin.rank.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                      </div>
                      <br>
                      @endif
                      {{-- @if($check_role == 1  ||key_exists(5, $check_role))
                      <div class="float-left ml10" >
                        <i class="fas fa-times mr12" ></i>
                        <a  class="setting-item delete text-danger action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Xóa</a>
                      </div>
                      @endif --}}
                      <div class="clear-both"></div>
                    </div>
                  </td>
                </tr>
                @empty
                <td colspan="7">Chưa có dữ liệu</td>
                @endforelse

              </tbody>
            </table>
          </form>
        </div>
        <div class="d-flex align-items-center justify-content-between my-4">
          <div class=" d-flex box-panage align-items-center">
            {{-- <div class=" d-flex mb-2">
              <img src="image/manipulation.png" alt="" id="btnTop">
              <div class="btn-group ml-1">
                <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                  Thao tác
                </button>
                <div class="dropdown-menu">                     
                  @if($check_role == 1 || key_exists(5, $check_role))
                  <a class="dropdown-item moveToTrash" type="button" href="javascript:{}" >
                    <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i>Thùng rác
                  </a>
                  @endif
                  @if(is_array($check_role) && !key_exists(2, $check_role) && !key_exists(5, $check_role))
                  <p class="dropdown-item m-0 disabled">
                    Bạn không có quyền
                  </p>
                  @endif
                </div>
              </div>
            </label>
          </div> --}}
          <div class="d-flex align-items-center justify-content-between mx-4 mb-2">
            <div class="d-flex mr-2 align-items-center">Hiển thị</div>
            <label class="select-custom2">
              <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
                <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
              </select>
            </label>
          </div>
          {{-- @if($check_role == 1 || key_exists(8, $check_role))
          <div class="d-flex flex-row align-items-center view-trash" style="margin-top: -5px">
            <i class="far fa-trash-alt mr-2" style="margin-top: -5px"></i>
            <div class="link-custom" >
              <a href="{{route('admin.rank.trash')}}"><span  style="color: #347ab6">Xem thùng rác</span>
                <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500;">{{$trash_num}}</span>
              </a>
            </div>
          </div>
          @endif --}}
        </div>
        <div class="d-flex align-items-center">
          <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
          @if($list)
          {{ $list->render('Admin.Layouts.Pagination') }}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
</section>
<!-- /.content -->
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <form action="" method="post">
        @csrf
          <div class="form-group w-100 p-2 my-2">
              <label>{{ $guide->config_name }}</label>
              <textarea name="content" class="js-admin-tiny-textarea">{!! $guide->config_value !!}</textarea>
          </div>
          <div class="form-group d-flex justify-content-center mb-4">
              <button class="btn btn-primary no-border no-radius">Lưu</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('Script')
<script src="js/table.js" type="text/javascript"></script>
<script>
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
        window.location.href = "/admin/rank/delete/" + id + "/" + created_by;
      }
    });
  });
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


  function hideTextDateStart(){
    $('#txtDateStart').hide();
  }
  function hideTextDateEnd(){
    $('#txtDateEnd').hide();
  }
  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
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
@if(count($errors) > 0)
@foreach($errors->all() as $error)
toastr.error("{{ $error }}");
@endforeach
@endif
@endsection
