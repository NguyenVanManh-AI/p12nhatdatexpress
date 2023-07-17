@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mẫu email | Chiên dịch email')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
{{-- css reponsive panagetion --}}
<style type="text/css">
  @media only screen and (max-width: 779px) {
    .box-panage{
      display:inline !important;
    }
  }
  .box_input{
    height: 50px !important;
  }
</style>
<!-- Content Header (Page header) -->
<div class="col-sm-12 mbup30">
  <div class="row m-0 px-2 pt-3">
    <ol class="breadcrumb mt-1">
      <li class="list-box px-2 pt-1 active check">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </li>
      {{-- kiểm tra phân quyền thùng rác --}}
      @if($check_role == 1  ||key_exists(5, $check_role))
      <li class="phay ml-2 " style="margin-top: 2px !important">
        /
      </li>
      <li class="recye px-2 pt-1 ml-1">
        <a href="{{route('admin.email-campaign.trash-list-template')}}">
          Thùng rác
        </a>
      </li>
      @endif
      {{-- kiểm tra phân quyền thêm --}}
      @if($check_role == 1  ||key_exists(1, $check_role))
      <li class="ml-2 phay">
        /
      </li>   
      <li class="add px-2 pt-1 ml-1 check">
        <a href="{{route('admin.email-campaign.add-mail-template')}}">
          <i class="fa fa-edit mr-1"></i>Thêm
        </a>
      </li>
      @endif
    </ol>
  </div>
</div><!-- /.col -->

<!-- Filter -->
<div class="container-fluid px-3 mt-2">
  <form method="get" action="">
    <div class="row m-0">
      {{-- Tìm kiếm theo từ khóa --}}
      <div class="col-12 col-sm-12 col-md-3 col-lg-3 box_input px-0 mb-2">
       <div class="input-reponsive-search ">
         <input class="form-control required" type="" name="keyword" placeholder="Nhập từ khóa" value="{{ app('request')->input('keyword') }}">
       </div>
     </div>
     <div class="search-reponsive  col-12 col-sm-12 col-md-9 col-lg-9 pl-0">
      <div class="row m-0">
        <div id="from_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
          <div  style="position: relative">
            {{-- Tìm kiếm theo từ ngày --}}
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
            {{-- Tìm kiếm theo đến ngày --}}
            @if(app('request')->input('to_date') == "")
            <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
              <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>         
            </div>
            @endif
            <input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
            <div id="appendDateError"></div>
          </div>
        </div>
        <script type="text/javascript">
          {{-- Ẩn hiện từ ngày - đến ngày trong ô input --}}
          $('#from_date_box').click(function(){
            $('#from_date_text').hide();
          })
          $('#to_date_box').click(function(){
            $('#to_date_text').hide();
          })
        </script>
        {{-- submit tìm kiếm --}}
        <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
          <button class=" search-button btn btn-primary w-100" style="height: 37px;"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>                      
        </div>
      </div>
    </div>
  </div>
</form>
</div>
<!-- ./Filter -->

<div class="content-header" style="margin-top: -10px">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 font-weight-bold">DANH SÁCH MẪU EMAIL</h1>
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
          <form action="{{route('admin.email-campaign.delete-mail-template-list')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  <th scope="row" class="active" width="3%">
                    {{-- chọn tất cả --}}
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" width="3%">ID</th>
                  <th scope="col" width="8%">Thứ tự</th>
                  <th scope="col" width="30%">Tiêu đề</th>
                  <th scope="col" width="12%">Ngày tạo</th>
                  <th scope="col" width="10%">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                {{-- lặp lấy ra danh sách --}}
                @forelse($list as $item)
                <tr>
                  <td class="active">
                    {{-- chọn nhiều dòng --}}
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td>
                  {{-- id mẫu mail --}}
                  <td >{{$item->id}}</td>
                  <td class="input_rounded">
                   {{-- sửa thứ tự --}}
                   <input type="text" value="{{$item->show_order}}" name="show_order[{{$item->id}}]">
                 </td>

                 <td>
                  {{-- tiêu đề mail --}}
                  {{$item->template_title}}
                </td>
                <td>
                  {{-- ngày tạo --}}
                  {{date('d/m/Y H:i',$item->created_at)}}
                </td>
                <td class="text-left">
                  <div>
                   {{-- kiểm tra phân quyền chỉnh sửa --}}
                   @if($check_role == 1  ||key_exists(2, $check_role))
                   <div class="float-left ml10 mb-2" >
                    <i class="fas fa-cog mr-2" ></i>
                    <a href="{{route('admin.email-campaign.edit-mail-template',[$item->id,\Crypt::encryptString($item->created_by)])}}" >Chỉnh sửa</a>
                  </div>
                  @endif
                  {{-- kiểm tra phân quyền xóa --}}
                  @if($check_role == 1  ||key_exists(7, $check_role))
                  <div class="float-left ml10 mb-2" >
                    <i class="fas fa-times mr12" style="margin-left: 2px"></i>
                    <a  class="setting-item delete text-danger action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Xóa</a>
                  </div>
                  @endif                       
                  <div class="clear-both"></div>
                </div>
              </td>
            </tr>
            @empty
            <td colspan="9">Chưa có dữ liệu</td>
            @endforelse

          </tbody>
        </table>
      </form>
    </div>
    <div class="table-bottom d-flex align-items-center justify-content-between  pb-5" style="margin-bottom: 90px !important">
      <div class=" d-flex box-panage align-items-center">
        <div class=" d-flex mb-2">
          <img src="image/manipulation.png" alt="" id="btnTop">
          <div class="btn-group ml-1">
            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
              Thao tác
            </button>
            <div class="dropdown-menu">
              {{-- kiểm tra phân quyền cập nhật để cập nhật thứ tự hiển thị --}}
              @if( $check_role == 1 || key_exists(2, $check_role))
              <a class="dropdown-item updateShowOrder" type="button" href="javascript:{}">
                <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i> Cập nhật
              </a>
              @endif
              {{-- kiểm tra phân quyền thùng rác để xóa nhiều --}}
              @if($check_role == 1 || key_exists(5, $check_role))
              <a class="dropdown-item moveToTrash" type="button" href="javascript:{}" >
                <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i>Thùng rác
              </a>
              @endif
              {{-- kiểm tra nếu không có quyền --}}
              @if(is_array($check_role) && !key_exists(2, $check_role) && !key_exists(5, $check_role))
              <p class="dropdown-item m-0 disabled">
                Bạn không có quyền
              </p>
              @endif
            </div>
          </div>
        </label>
      </div>
      <div class="d-flex align-items-center justify-content-between mx-4 mb-2">
        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
        <label class="select-custom2">
          {{-- số dòng hiển thị danh sách --}}
          <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
          </select>
        </label>
      </div>
      {{-- kiểm tra phân quyền quản lý thùng rác --}}
      @if($check_role == 1 || key_exists(8, $check_role))
      <div class="d-flex flex-row align-items-center view-trash" style="margin-top: -5px">
        <i class="far fa-trash-alt mr-2" style="margin-top: -5px"></i>
        <div class="link-custom" >
          <a href="{{route('admin.email-campaign.trash-list-template')}}"><span  style="color: #347ab6">Xem thùng rác</span>
            <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500;">{{$trash_num}}</span>
          </a>
        </div>
      </div>
      @endif
    </div>
    <div class="d-flex align-items-center">
      {{-- tổng cộng mẫu mail --}}
      <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
      @if($list)
      {{-- phân trang --}}
      {{ $list->render('Admin.Layouts.Pagination') }}
      @endif
    </div>
  </div>
</div>
</div>
</div>
</section>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js" type="text/javascript"></script>
<script>
  {{-- sửa thứ tự hiển thị --}}
  {{-- click updateShowOrder --}}
  $('.dropdown-item.updateShowOrder').click(function () {
    {{-- lấy các item được chọn --}}
    const selectedArray = getSelected();
    {{-- nếu có item --}}
    if (selectedArray)
      {{-- chỉnh sửa url và submit các item được chọn qua controller --}}
      $('#formtrash').attr('action', "/admin/mail-campaign/template/change-show-order").submit();
  })
  {{-- xóa 1 item --}}
  {{-- click nút có class là delete --}}
  $('.delete').click(function () {
    {{-- lấy ra id --}}
    var id = $(this).data('id');
    {{-- lấy ra người tạo --}}
    var created_by = $(this).data('created_by');
    {{-- Hiển thị thông báo xác nhận xóa --}}
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
      {{-- nếu nhấn đồng ý --}}
      if (result.isConfirmed) {
        {{-- truy cập link để xóa item --}}
        window.location.href = "/admin/mail-campaign/template/delete-mail-template/" + id + "/" + created_by;
      }
    });
  });
  //----------------------------------------------thao tác list
  //xóa nhiều item
  //click button có class moveToTrash
  $('.moveToTrash').click(function () {
    //lấy ra id 
    var id = $(this).data('id');
    //hiển thị thông báo
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
      //nếu nhấn xác nhận
      if (result.isConfirmed) {
        //submit form
        $('#formtrash').submit();
      }
    });
  });

  //Phân trang
  function submitPaginate(event){
    //lấy ra uri
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
  //kiểm tra đến ngày phải lớn hơn từ ngày
  setMinMaxDate('#handleDateFrom', '#handleDateTo')
  function setMinMaxDate(inputElementStart, inputElementEnd){
    //lấy ra value của ngày đến
    if ($(inputElementStart).val()) $(inputElementEnd).attr('min',$(inputElementStart).val());
    //lấy ra value của ngày đến
    if ($(inputElementEnd).val()) $(inputElementStart).attr('max',$(inputElementEnd).val());
    //Khi thay đổi thì set min
    $(inputElementStart).change(function (){
      $(inputElementEnd).attr('min',$(inputElementStart).val());
    });
    //Khi thay đổi thì set max
    $(inputElementEnd).change(function (){
      $(inputElementStart).attr('max',$(inputElementStart).val());
    });
  }
</script>
{{-- nếu lỗi trả về lớn hơn 0 --}}
@if(count($errors) > 0)
{{-- lặp và hiển thị ra giao diện các lỗi đó --}}
@foreach($errors->all() as $error)
toastr.error("{{ $error }}");
@endforeach
@endif
@endsection
