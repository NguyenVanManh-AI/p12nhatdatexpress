@extends('Admin.Layouts.Master')
@section('Title', 'Chi tiết chiến dịch | Chiến dịch email')
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
      <li class="recye px-2 pt-1 check">
        <a href="{{route('admin.email-campaign.list-campaign')}}">
          <i class="fa fa-th-list mr-1"></i>Danh sách
        </a>
      </li>  
    </ol>
  </div>
</div><!-- /.col -->

<!-- ./Filter -->

<div class="content-header" style="margin-top: -10px">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 font-weight-bold">DANH SÁCH CHI TIẾT GỬI MAIL</h1>
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
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  {{-- <th scope="row" class="active" width="3%"> --}}
                    {{-- chọn tất cả --}}
                   {{--  <input type="checkbox" class="select-all checkbox" name="select-all" />
                 </th> --}}
                 <th scope="col" style="width: 3%">ID</th>
                 <th scope="col" style="width: 26%">Email gửi</th>
                 <th scope="col" style="width: 26%">Email nhận</th>
                 <th scope="col" style="width: 10%">Trạng thái</th>
                 <th scope="col" style="width: 10%">Ngày gửi</th>
               </tr>
             </thead>
             <tbody>
              {{-- lặp lấy ra danh sách --}}
              @forelse($listMailSend as $item)
              <tr>
                {{-- <td class="active"> --}}
                  {{-- chọn nhiều dòng --}}
                    {{-- <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td> --}}
                  {{-- id mẫu mail --}}
                  <td >{{$item->id}}</td>
                  <td>
                    {{-- tiêu đề mail --}}
                    {{$item->mail_username}}
                  </td>
                  <td>
                   {{$item->user_email}}
                 </td>
                 <td>
                  @if($item->receipt_status ==0)
                  <p class="mb-0 text-warning">Chờ gửi</p>
                  @elseif($item->receipt_status ==1)
                  <p class="mb-0 text-success">Thành công</p>
                  @elseif($item->receipt_status ==2)
                  <p class="mb-0 text-danger">Gửi lỗi</p>
                  @endif
                </td>
                <td>
                  @if($item->receipt_time == null)
                  ---
                  @else
                  {{ date('d/m/Y H:i:s', $item->receipt_time) }}
                  @endif
                </td>
              </tr>
              @empty
              <td colspan="9">Chưa có dữ liệu</td>
              @endforelse

            </tbody>
          </table>
      </div>
      <div class="table-bottom d-flex align-items-center justify-content-between  pb-5">
        <div class=" d-flex box-panage align-items-center">
          <div class=" d-flex mb-2">
            <img src="image/manipulation.png" alt="" id="btnTop">
            <div class="btn-group ml-1">
              <button disabled="" type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                Thao tác
              </button>
              <div class="dropdown-menu">          
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

      </div>
      <div class="d-flex align-items-center">
        {{-- tổng cộng mẫu mail --}}
        <div class="count-item">Tổng cộng: @empty($listMailSend) {{0}} @else {{$listMailSend->total()}} @endempty items</div>
        @if($listMailSend)
        {{-- phân trang --}}
        {{ $listMailSend->render('Admin.Layouts.Pagination') }}
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
        window.location.href = "/admin/mail-campaign/campaign/delete-mail-campaign/" + id + "/" + created_by;
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
