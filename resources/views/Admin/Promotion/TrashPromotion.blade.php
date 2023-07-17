@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mã khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

<div class="row m-0 px-3 pt-3">
  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.promotion.list-promotion')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
    {{-- @if($check_role == 1  ||key_exists(5, $check_role))
    <li class="phay ml-2">
      /
    </li>
    <li class="recye px-2 pt-1 ml-1 active check">
      <a href="{{route('admin.promotion.trash')}}">
        Thùng rác
      </a>
    </li>
    @endif --}}
    {{-- @if($check_role == 1  ||key_exists(1, $check_role))
    <li class="ml-2 phay">
      /
    </li>   
    <li class="add px-2 pt-1 ml-1 check">
      <a href="{{route('admin.promotion.add-promotion')}}">
        <i class="fa fa-edit mr-1"></i>Thêm
      </a>
    </li>
    @endif --}}
  </ol>
</div>
<h4 class="text-center font-weight-bold mb-3 mt-2">QUẢN LÝ THÙNG RÁC MÃ KHUYẾN MÃI</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{route('admin.promotion.untrashlist')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table"  style="min-width: 1050px">
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
                <span class="font-weight-bold">Ngày bắt đầu: </span><span >{{\Carbon\Carbon::parse($item->date_from)->format('d/m/Y h:i')}}</span><br>
                <span class="font-weight-bold">Ngày kết thúc: </span><span >{{\Carbon\Carbon::parse($item->date_to)->format('d/m/Y h:i')}}</span>
              </td>
              <td>
                @if($item->promotion_type == 1)
                Nạp coin
                @else
                Mua gói
                @endif
              </td>
              <td>
                <div class="flex-end">
                  @if($check_role == 1  ||key_exists(6, $check_role))
                  <div class="float-left ml-0" >
                    <a href="javascript:void(0);" class="setting-item delete action_delete btn btn-sm btn-success mb-2 mr-2" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" title="Khôi phục">
                      <i class="fas fa-redo"></i>
                    </a>
                  </div>
                  @endif
                  @if($check_role == 1)
                  {{-- @if($check_role == 1 || key_exists(5, $check_role)) --}}
                    <a href="javascript:void(0);" class="btn btn-sm btn-danger mb-2 mr-2 js-force-delete-item" data-id="{{ $item->id }}" title="Xóa hẳn">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                    {{-- <form action="{{ route('admin.promotion.force-delete-multiple', ['ids' => $item->id]) }}" class="d-inline-block" method="POST">
                      @csrf
                      <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert"
                        data-action="xóa hẳn" title="Xóa hẳn">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form> --}}
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

    <form action="{{ route('admin.promotion.force-delete-multiple') }}" class="force-delete-item-form d-none" method="POST">
      @csrf
      <input type="hidden" name="ids">
    </form>

    <div class="m-0 mt-3 mb-5 pb-5 w-100">
      <div class="w140 float-left mb-2" >
        <div class="manipulation d-flex mr-4">
          <img src="{{ asset("system/images/icons/manipulation.png")}}" id="btnTop">
          <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
          aria-expanded="false" data-flip="false" aria-haspopup="true">
          Thao tác
        </button>
        <div class="dropdown-menu">
          @if($check_role == 1 ||key_exists(6, $check_role))
          <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
            <i class="fas fa-undo-alt bg-red p-1 mr-2 rounded"
            style="color: white !important;font-size: 15px"></i>Khôi phục
            <input type="hidden" name="action" value="restore">
          </a>
          @endif
          @if($check_role == 1)
            <a class="dropdown-item js-force-delete-multiple" type="button" href="javascript:void(0);">
              <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i> Xóa hẳn
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
      <div class="manipulation d-flex mr-4">
       @if($check_role == 1  ||key_exists(8, $check_role))
       <div class="view-trash">
         @if($check_role == 1  ||key_exists(4, $check_role))
         <a href="{{route('admin.promotion.list-promotion')}}" class="btn btn-primary">
          Quay lại
        </a>
        @endif
      </div>
      @endif
    </div>
  </div>
  <div class="float-left mb-2" style="width: calc(100% - 800px);"></div>
  <div class="float-right mb-2 w370">
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
  $('#makhuyenmai').addClass('active');
  $('#nav-makhuyenmai').addClass('menu-is-opening menu-open');
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
      title: 'Xác nhận khôi phục',
      text: "Nhấn đồng ý thì sẽ tiến hành khôi phục!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/promotion/undelete/" + id+"/"+created_by;

      }
    });
  });
  $('.unToTrash').click(function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Xác nhận khôi phục',
      text: "Nhấn đồng ý thì sẽ tiến hành khôi phục !",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
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

  (() => {
    function forceDelete(id) {
        if (!id) return
        Swal.fire({
          title: 'Xác nhận xóa hẳn',
          text: "Sau khi xóa sẽ không thể khôi phục",
          icon: 'error',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Quay lại',
          confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.force-delete-item-form input[name="ids"]').val(id)
                $('.force-delete-item-form').trigger('submit')
            }
        })
    }

    function forceDeleteMultiple(selectedArray) {
      if (!selectedArray) return
      Swal.fire({
        title: 'Xác nhận xóa hẳn',
        text: "Sau khi xóa sẽ không thể khôi phục",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
      }).then((result) => {
        if (result.isConfirmed) {
          let ids = [];
          selectedArray.forEach(element => {
            if ($(element).val())
            ids.push($(element).val())
          })
          $('.force-delete-item-form input[name="ids"]').val(ids)
          $('.force-delete-item-form').trigger('submit')
        }
      })
    }

    $('.js-force-delete-multiple').click(function(e) {
      e.preventDefault()
      const selectedArray = getSelected();

      forceDeleteMultiple(selectedArray)
    })

    $('.js-force-delete-item').click(function(e) {
      e.preventDefault()
      forceDelete($(this).data('id'))
    })
  })()
</script>
@endsection
