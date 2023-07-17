@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mã khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

<div class="row m-0 px-3 pt-3">
  <ol class="breadcrumb mt-1">
    <li class="list-box px-2 pt-1 active check" >
      <i class="fa fa-th-list mr-1"></i>Danh sách
    </li>
    @if($check_role == 1  ||key_exists(5, $check_role))
    <li class="phay ml-2">
      /
    </li>
    <li class="recye px-2 pt-1 ml-1">
      <a href="{{route('admin.promotion.trash')}}">
        Thùng rác
      </a>
    </li>
    @endif
    @if($check_role == 1  ||key_exists(1, $check_role))
    <li class="ml-2 phay">
      /
    </li>   
    <li class="add px-2 pt-1 ml-1 check">
      <a href="{{route('admin.promotion.add-promotion')}}">
        <i class="fa fa-edit mr-1"></i>Thêm
      </a>
    </li>
    @endif
  </ol>
</div>
<form method="get" action="{{route('admin.promotion.list-promotion')}}" id="filter-promotion">
  <div class="box-fiter-reponsive">
    <div class="row m-0 px-3 pb-4 pt-3">  
      <div class="search-reponsive col-12 col-sm-12 col-md-8 col-lg-8">
        <div class="row m-0">
          <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
           <input id="handleDateFrom" name="date_from" class="start_day form-control float-left" type="date" placeholder="Ngày bắt đầu" required value="{{ app('request')->input('date_from') }}" >
         </div>
         <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
          <input id="handleDateTo" name="date_to" class="end_day form-control float-right" type="date" placeholder="Ngày kết thúc " required value="{{ app('request')->input('date_to') }}">
          <div id="appendDateError"></div>
        </div>
      </div>
    </div>
    <div class="comment-new-reponsive col-12 col-sm-12 col-md-3 col-lg-3">
      <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search mr-2" aria-hidden="true" ></i>Tìm kiếm</button>
    </div>
  </div>
</div>
</form>
<h4 class="text-center mb-3 font-weight-bold">QUẢN LÝ MÃ KHUYẾN MÃI</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid" >
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{route('admin.promotion.trashlist')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table" style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                  <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                  <th scope="col" style="width: 6%">STT</th>
                  <th scope="col" style="width: 6%">Mã</th>
                  <th scope="col" style="width: 13%">Đã dùng/Tổng số</th>

                  <th scope="col" style="width: 6%">Giá trị</th>
                  <th scope="col" style="width: 12%">Quyền sử dụng</th>
                  <th scope="col" style="width: 23%">Thông tin</th>
                  <th scope="col" style="width: 8%">Loại</th>
                  <th scope="col" style="width: 12%">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($list_promotion as $item )
                <tr>
                  <td class="active">
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td>
                  <td class="font-weight-bold">{{$item->id}}</td>
                  <td class="font-weight-bold">{{$item->promotion_code}}</td>

                  <td >
                    <span class="font-weight-bold">{{$item->used}}/<span class="text-danger">{{$item->num_use}}</span></span>
                  </td>
                  <td>
                    @if($item->promotion_unit == 0)
                    {{$item->value}}%
                    @else
                    {{$item->value}}đ
                    @endif
                  </td>

                  <td >
                    @if($item->is_all == 1)
                    <span>Tất cả</span>
                    @elseif($item->is_private ==1)
                    <span>Nhận trên trang</span>
                    @else
                    <span>Người dùng</span>
                    <span>[ID: {{$item->user_id_use}}]</span>
                    @endif
                  </td>
                  <td class="text-left">
                    <span class="font-weight-bold">Ngày tạo: </span><span >{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i')}}</span><br>
                    <span class="font-weight-bold">Ngày bắt đầu: </span><span >{{\Carbon\Carbon::parse($item->date_from)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y h:i')}}</span><br>
                    <span class="font-weight-bold">Ngày kết thúc: </span><span >{{\Carbon\Carbon::parse($item->date_to)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y h:i')}}</span>
                  </td>
                  <td>
                    @if($item->promotion_type == 1)
                    Nạp coin
                    @else
                    Mua gói
                    @endif
                  </td>
                  <td>
                    <div>
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      <div class="float-left ml-2">
                        <i class="fas fa-cog mr-2"></i>
                        <a href="{{route('admin.promotion.edit_promotion',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                      </div>
                      <br>
                      @endif
                      @if($check_role == 1  ||key_exists(5, $check_role))
                      <div class="float-left ml10" >
                        <i class="fas fa-times mr12" ></i>
                        <a  class="setting-item delete text-danger action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Xóa</a>
                      </div>
                      @endif
                      <div class="clear-both"></div>
                    </div>
                  </td>
                </tr>

                @endforeach

              </tbody>
            </table>
          </form>
        </div>

        <div class=" m-0 mt-3 mb-5"  style="width: 100%">
          <div class="w140 float-left mb-2" >
            <div class="manipulation d-flex mr-4">
              <img src="{{ asset("system/images/icons/manipulation.png")}}" id="btnTop">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
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
          @if($check_role == 1  ||key_exists(8, $check_role))
          <div class="view-trash">
           <a href="{{route('admin.promotion.trash')}}"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
           <span class="count-trash">
            @if(isset($count_trash))
            {{$count_trash}}
            @endif
          </span>
        </div>
        @endif
      </div>
    </div>
    <div class="float-left mb-2" style="width: calc(100% - 800px);"></div>
    <div class="float-right mb-2 ">
     <div class="d-flex align-items-center">
      <div class="count-item" >Tổng cộng: @empty($list_promotion) {{0}} @else {{$list_promotion->total()}} @endempty items</div>
      <div class="count-item-reponsive" style="display: none">@empty($list_promotion) {{0}} @else {{$list_promotion->total()}} @endempty items</div>
      <div class="float-right">
       @if($list_promotion)
       {{ $list_promotion->render('Admin.Layouts.Pagination') }}
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
<script type="text/javascript">

  $('#set-active5').addClass('active2');
  // $('.child5').addClass('active');
  // $('.set-active5').addClass('menu-is-opening menu-open');
</script>


<script>
  @if(count($errors) > 0)
  toastr.error("Vui lòng kiểm tra các trường");
  @endif
</script>

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
        window.location.href = "/admin/promotion/delete/" + id + "/" + created_by;
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
</script>

<script type="text/javascript">
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/filter-promotion.validate.js') }}"></script>
@endsection
